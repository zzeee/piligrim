package qrcode

import qrcode "github.com/skip2/go-qrcode"
err := qrcode.WriteFile("https://example.org", qrcode.Medium, 256, "qr.png")
  