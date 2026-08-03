<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $subject }}</title>
</head>
<body style="margin:0;padding:0;background-color:#eef2f7;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation" style="background-color:#eef2f7;">
<tr>
<td align="center" style="padding:24px 16px;">
<table width="480" cellpadding="0" cellspacing="0" border="0" role="presentation" style="max-width:480px;width:100%;background-color:#ffffff;border-radius:16px;overflow:hidden;">
<tr>
<td align="center" style="background-color:#eff6ff;padding:28px 24px;">
<img src="{{ asset('images/simonas_logo.png') }}" width="56" height="56" alt="SIMONAS" style="display:block;height:56px;width:56px;border-radius:14px;margin:0 0 10px;">
<div style="color:#1d4ed8;font-size:19px;font-weight:800;">SIMONAS</div>
<div style="color:#3b82f6;font-size:10px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;margin-top:2px;">Digital Asrama YAPI</div>
</td>
</tr>
<tr>
<td style="padding:28px 28px 8px;">
<h1 style="margin:0 0 14px;font-size:17px;color:#1e293b;">{{ $greeting }}</h1>
@foreach ($introLines as $line)
<p style="margin:0 0 14px;font-size:14px;line-height:1.55;color:#475569;">{{ $line }}</p>
@endforeach
</td>
</tr>
<tr>
<td align="center" style="padding:10px 28px 8px;">
<table cellpadding="0" cellspacing="0" border="0" role="presentation">
<tr>
<td align="center" style="border-radius:8px;background-color:#2563eb;">
<a href="{{ $actionUrl }}" target="_blank" style="display:inline-block;padding:12px 28px;font-size:14px;font-weight:700;color:#ffffff;text-decoration:none;">{{ $actionText }}</a>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td style="padding:8px 28px 28px;">
@foreach ($outroLines as $line)
<p style="margin:14px 0 0;font-size:13px;line-height:1.55;color:#64748b;">{{ $line }}</p>
@endforeach
</td>
</tr>
<tr>
<td style="padding:16px 28px;border-top:1px solid #eef2f7;">
<p style="margin:0;font-size:11px;line-height:1.5;color:#94a3b8;text-align:center;">
&copy; {{ date('Y') }} SIMONAS Digital Asrama YAPI. Semua hak dilindungi.<br>
Email ini dikirim otomatis, mohon tidak membalas ke alamat ini.
</p>
</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>
