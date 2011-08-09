<?php
#**************************************************************************
#  openSIS is a free student information system for public and non-public 
#  schools from Open Solutions for Education, Inc. web: www.os4ed.com
#
#  openSIS is  web-based, open source, and comes packed with features that 
#  include student demographic info, scheduling, grade book, attendance, 
#  report cards, eligibility, transcripts, parent portal, 
#  student portal and more.   
#
#  Visit the openSIS web site at http://www.opensis.com to learn more.
#  If you have question regarding this system or the license, please send 
#  an email to info@os4ed.com.
#
#  This program is released under the terms of the GNU General Public License as  
#  published by the Free Software Foundation, version 2 of the License. 
#  See license.txt.
#
#  This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU General Public License for more details.
#
#  You should have received a copy of the GNU General Public License
#  along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
#  System modified by SerInformaticos.es to convert this great soft to be
#  a multilanguage software
#
#  You can contact us at info@serinformaticos.es
#
#
#***************************************************************************************
// # FErArg
include ("../translate/langpref.php");
include ("../translate/lang/en.php");
include ("../translate/lang/$langpref");

?><?php 
echo '<script type="text/javascript">
var page=parent.location.href.replace(/.*\//,"");
if(page && page!="index.php" ){
	window.location.href="index.php";
	}

</script>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Untitled Document</title>
    <link rel="stylesheet" href="../styles/installer.css" type="text/css" />
    <script type="text/javascript" src="js/validator.js"></script>
    <script type="text/javascript">
function showAlert() {
    var divAlert = document.getElementById('divAlert');
    var divConnInfo = document.getElementById('divConnInfo');

    divAlert.style.display='';
    divConnInfo.style.display='none';
}
function hideAlert() {
    var divAlert = document.getElementById('divAlert');
    var divConnInfo = document.getElementById('divConnInfo');

    divAlert.style.display='none';
    divConnInfo.style.display='';
}
    </script>
</head>
<body>
    <?php
    error_reporting(0);
    session_start();

    if($_REQUEST['mod']=='upgrade')
    {
        echo '<div class="heading">'.$step12;
        $_SESSION['mod'] = 'upgrade';
    }
    else
    {
        echo '<div class="heading">'.$step13;
    }

    echo '<div id="divAlert" style="display:none; height:270px;">';

    $myFile = "../data.php";
    $fh1 = fopen($myFile, 'w');

    if ($fh1 == FALSE)
    {
        echo '<br />';
        echo '<br />'.$step14;
        echo '<br />';
        echo '<br />'.$step15;
        echo '<br />'.$step16;
    }
    fclose($fh1);

    $myFile = "../assets/StudentPhotos/dummy.txt";
    $fh2 = fopen($myFile, 'w');

    if ($fh2 == FALSE)
    {
        echo '<br />';
        echo '<br />'.$step17;
        echo '<br />'.$step18;
    }
    else
    {
        unlink($myFile);
    }
    fclose($fh2);

    $myFile = "../assets/UserPhotos/dummy.txt";
    $fh3 = fopen($myFile, 'w');

    if ($fh3 == FALSE)
    {
        echo '<br />';
        echo '<br />'.$step19;
        echo '<br />'.$step110;
    }
    else
    {
        unlink($myFile);
    }
    fclose($fh3);

    echo '<br />';
    echo '<br />';
    echo '<br />'.$step111;
    echo '<br />';
    echo '<br />';
    echo '<input type="button" value='.$step123.' class="btn_wide" onclick="hideAlert()" />';
    echo '</div>';

    if ($fh1 && $fh2 && fh3)
        // show Connection information fields
        echo '<div id="divConnInfo" style="';
    else
        // hide Connection information fields
        echo '<div id="divConnInfo" style="display:none; ';
    
    ?>
	<?php
	if($_SESSION['mod']!='upgrade')
	{
	echo 'background-image:url(images/step1_new.gif); background-repeat:no-repeat; background-position:50% 20px; height:270px;">';	
	}
	else
	{
	echo 'background-image:url(images/step1.gif); background-repeat:no-repeat; background-position:50% 20px; height:270px;">';
	}
	?>

    <form name='step1' id='step1' method="post" action="ins1.php<?php echo ($_REQUEST['mod']=='upgrade')?'?mod=upgrade':''; ?>">
        <table border="0" cellspacing="6" cellpadding="3" align="center">
            <tr>
                <td  align="center" style="padding-top:36px">
                    <?php
                    if($_SESSION['mod']!='upgrade')
                    {
                    echo $step120;
                    }
                    else
                    {
                    echo $step124;
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td align="center"><strong><?php echo $step112; ?></strong></td>
            </tr>
            <tr>
                <td align="left" valign="top"><table width="245" border="0" cellpadding="2" cellspacing="0" id="table1">
                        <tr>
                            <td width="137"><?php echo $step113; ?></td>
                            <td><input type="text" name="server" size="20" value="localhost" /></td>
                        </tr>
                        <tr>
                            <td width="137"><?php echo $step114; ?></td>
                            <td><input type="text" name="port" size="20" value="3306" /></td>
                        </tr>
                        <tr>
                            <td width="137"><?php echo $step115; ?></td>
                            <td><input type="text" name="addusername" size="20" value="root" /></td>
                        </tr>
                        <tr>
                            <td width="137"><?php echo $step116; ?></td>
                            <td><input type="password" name="addpassword" size="20" /></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type="submit" value=<?php echo $step122; ?> class="btn_wide" name="DB_Conn" /></td>
                        </tr>
                    </table>
                    <script type="text/javascript">
<?php
if ($fh1 && $fh2 && fh3)
    echo 'hideAlert();';
else
    echo 'showAlert();';
?>
var frmvalidator  = new Validator("step1");
frmvalidator.addValidation("server","req",$step117);
frmvalidator.addValidation("port","req",$step118);
frmvalidator.addValidation("addusername","req",$step119);
                    </script>
                </td>
            </tr>
        </table>
    </form>
    </div>
</body>
</html>
