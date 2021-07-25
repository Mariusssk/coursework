<?php
namespace Template\Email;

function getEmailFooter($type) {
	$footer = getFooterTop();
	
	$footer .= getFooterBottom();
	return($footer);
}

function getFooterTop() {
	$top = '
	<!--[if mso]>
				</td>
            </tr>
        </table>
    </div>
	<![endif]-->
	<table border="0" cellpadding="0" cellspacing="0" >
		<tr><td height="10"></td></tr>
		<tr style="background-color: #f5f5f5; font-family: arial,sans-serif; font-size: 14px;">
		<td style="width: 10%"></td>
		<td height="200" width="3000" align="center" style="line-height: 130%;">
			<p> '.EMAIL_BASIC_CONFIDENTIAL_INFO.'</p>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
				<tr><td height="10"></td></tr>
				<tr>
					<td style="background:none; border-bottom: 2px solid #acafb1; height:2px; width:100%; margin:0px 0px 0px 0px;"></td>
				</tr>
				<tr><td height="10"></td></tr>
			</table>
	';
	return($top);
}

function getFooterBottom() {
	$bottom = '
				<p>'.EMAIL_BASIC_ADOBE_INFO.': <a href="http://get.adobe.com/reader/">http://get.adobe.com/reader/</a></p>
			
			</td>
			<td style="width: 10%"></td>
		</td></tr></table>
	</body>
	</html>
	';
	return($bottom);
}
	

