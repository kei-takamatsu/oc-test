<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="8%">Dear <?php echo ucfirst($firstname) ?></td>
        <td width="59%">&nbsp;</td>
        <td width="33%">&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>To reset your password, Please click the link below.</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td><a href="<?php echo $this->Html->url(array(
                    'controller' => 'users', 'action' => 'reset_password', $token
            ), true) ?>"><?php echo $this->Html->url(array(
                        'controller' => 'users', 'action' => 'reset_password', $token
                ), true) ?></a>
        </td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>Logicky Crowd Funding</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>
