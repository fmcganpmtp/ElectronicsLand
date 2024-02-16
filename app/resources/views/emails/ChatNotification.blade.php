<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="{{asset('images/favicon.ico')}}" type="image/x-icon">
<title>Fmcg land</title>
<body style="margin:0px 0px; padding:0px; font-family:Tahoma, Geneva, sans-serif;color:#333333; font-size:14px;  font-weight:lighter;">
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="background:#f0ddcc; padding:10px; padding-top:0px;">
<tr>
<td>
<p style="height:10px; margin:0px 0px; padding:0px; background:#f0ddcc url('../images/pattern1.jpg') top;"></p>
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="background:#FFF; border:1px solid #f1f1f1;max-width:500px; margin-top:10px; 
border-top-right-radius:10px; border-top-left-radius:10px;">
  <tr style=" margin:0px 0px; padding:0px;">
    <td colspan="5"><p style="margin:0px 0px;  padding:0px;border-top-right-radius:10px; text-align:center; border-top-left-radius:10px; border-bottom:3px solid #e86a6a; background:#f5f5f5;"><img src="{{url('/public/images/logo_mail.png')}}" style="width:250px; text-align:center; margin:30px auto;" /></p></td>
  </tr>
  <tr>
    <td colspan="5">
        <p style="margin:20px; font-weight:lighter; line-height:25px; text-align:center; margin-bottom:20px; background:url(images/footer-patern.png) repeat-x bottom; padding-bottom:30px;"
    >
<div  style="margin-left:3%;">
<h1 style="  font-size: 18px; ">
    Hi {{$user}},<br>
You have received a chat message from {{$messaged_user}}. Go to the fmcgland website to see and answer the message.<br><br>
with kind regards,<br><br>
Fmcgland team.
</h1>
</div>
</p>
</td>
  </tr>
  <tr>
    <td colspan="5">
    </td><!---outer--->
  </tr>
  <tr>
  <p></p>
  </tr>
  <tr>
    <td style="background:#1f1f1f; color:#FFF; font-size:13px; padding:15px 25px;">
	<p  style="margin:5px">E-mail:&nbsp; <a target="_blank" href="mailto:support@fmcgland.com" style="color:#ca5659; text-decoration:none;">support@fmcgland.com</a></p>
    </td>
  </tr>
 <!-- <tr>
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>-->
</table>
</td></tr>
</table>
</body>
</html>