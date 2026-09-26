#!/usr/bin/env python3
"""Lint design system RATA.

1. Kontras: membaca token @theme di resources/css/app.css lalu mengecek
   pasangan teks/latar wajib terhadap WCAG 2.2 AA.
2. Konsistensi: memindai resources/views/**/*.blade.php untuk warna di luar
   token (hex manual, palet default Tailwind seperti gray-/blue-/red-, dsb.).

Jalankan dari root repo:  python .claude/skills/rata-design/scripts/lint_design.py
Exit code 1 bila ada pelanggaran.
"""
import re
import sys
from pathlib import Path

ROOT = Path.cwd()
CSS = ROOT / "resources/css/app.css"
VIEWS = ROOT / "resources/views"

# (foreground, background, minimum) — nama token tanpa prefix --color-
PAIRS = [
    ("ink", "surface", 4.5), ("ink-soft", "surface", 4.5),
    ("muted", "surface", 4.5), ("muted", "canvas", 4.5),
    ("field", "surface", 3.0), ("field", "canvas", 3.0),
    ("surface", "brand-600", 4.5), ("brand-700", "brand-50", 4.5),
    ("brand-600", "surface", 4.5), ("brand-500", "surface", 3.0),
    ("surface", "ai-600", 4.5), ("ai-700", "ai-50", 4.5),
    ("success-700", "success-50", 4.5), ("warning-700", "warning-50", 4.5),
    ("danger-700", "danger-50", 4.5), ("surface", "danger-600", 4.5),
] + [
    pair for n in range(5) for pair in (
        (f"level-{n}-ink", f"level-{n}-soft", 4.5),
        (f"level-{n}", "surface", 3.0),
    )
]

TAILWIND_PALETTE = (
    "slate|gray|zinc|neutral|stone|red|orange|amber|yellow|lime|green|emerald|"
    "teal|cyan|sky|blue|indigo|violet|purple|fuchsia|pink|rose"
)
FORBIDDEN = [
    (re.compile(r"#[0-9a-fA-F]{3,8}\b(?![^<]*</(?:code|pre)>)"), "hex manual - pakai token"),
    (re.compile(rf"\b(?:bg|text|border|ring|from|to|via|fill|stroke|outline|divide|shadow)-(?:{TAILWIND_PALETTE})-\d{{2,3}}\b"),
     "palet default Tailwind - pakai token (brand/ai/success/warning/danger/level/ink/muted/line)"),
    (re.compile(r"\b(?:bg|text|border)-\[#"), "warna arbitrary - tambahkan token di @theme"),
]
# File yang boleh berisi hex (dokumentasi token / SVG logo).
ALLOW_HEX = {"ui-kit.blade.php", "logo.blade.php"}


def luminance(hex_color: str) -> float:
    h = hex_color.lstrip("#")
    if len(h) == 3:
        h = "".join(c * 2 for c in h)
    channels = [int(h[i:i + 2], 16) / 255 for i in (0, 2, 4)]
    channels = [c / 12.92 if c <= 0.03928 else ((c + 0.055) / 1.055) ** 2.4 for c in channels]
    return 0.2126 * channels[0] + 0.7152 * channels[1] + 0.0722 * channels[2]


def contrast(a: str, b: str) -> float:
    hi, lo = sorted((luminance(a), luminance(b)), reverse=True)
    return (hi + 0.05) / (lo + 0.05)


def check_contrast() -> int:
    tokens = dict(re.findall(r"--color-([\w-]+):\s*(#[0-9a-fA-F]{3,8})", CSS.read_text(encoding="utf-8")))
    failures = 0
    for fg, bg, minimum in PAIRS:
        if fg not in tokens or bg not in tokens:
            print(f"  MISSING  token {fg if fg not in tokens else bg}")
            failures += 1
            continue
        ratio = contrast(tokens[fg], tokens[bg])
        if ratio < minimum:
            print(f"  FAIL     {fg} on {bg}: {ratio:.2f} (min {minimum})")
            failures += 1
    print(f"Kontras: {len(PAIRS) - failures}/{len(PAIRS)} pasangan lolos")
    return failures


def check_views() -> int:
    failures = 0
    for path in sorted(VIEWS.rglob("*.blade.php")):
        for lineno, line in enumerate(path.read_text(encoding="utf-8").splitlines(), 1):
            if line.strip().startswith("{{--"):
                continue
            for pattern, message in FORBIDDEN:
                if message.startswith("hex") and path.name in ALLOW_HEX:
                    continue
                for match in pattern.finditer(line):
                    print(f"  {path.relative_to(ROOT)}:{lineno}  '{match.group(0)}'  {message}")
                    failures += 1
    print(f"Konsistensi view: {failures} pelanggaran")
    return failures


if __name__ == "__main__":
    total = check_contrast() + check_views()
    sys.exit(1 if total else 0)
