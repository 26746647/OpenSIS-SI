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

error_reporting(0);
session_start();
include("custom.class.php");
$mysql_database=$_SESSION['db'];
$dbconn = mysql_connect($_SESSION['server'],$_SESSION['username'],$_SESSION['password']) or die() ;

mysql_select_db($mysql_database);
$proceed=mysql_query("SELECT name
FROM APP
WHERE value LIKE  '4.6%' OR value LIKE '4.7%'");
$proceed=mysql_fetch_assoc($proceed);
if($proceed['name']){
	$date_time=date("m-d-Y");
    $Export_FileName=$mysql_database.'_'.$date_time.'.sql';
	$myFile = "upgrade.sql";
    executeSQL($myFile);
	backup_db($mysql_database,$Export_FileName);
	
	$res_student_field='SHOW COLUMNS FROM STUDENTS WHERE FIELD LIKE "CUSTOM_%"';
	$objCustomStudents=new custom($mysql_database);
	$objCustomStudents->set($res_student_field,'STUDENTS');
	
	$res_staff_field='SHOW COLUMNS FROM STAFF WHERE FIELD LIKE "CUSTOM_%"';
	$objCustomStaff=new custom($mysql_database);
	$objCustomStaff->set($res_staff_field,'STAFF');
	
	mysql_query("drop database $mysql_database");
	mysql_query("create database $mysql_database");
	mysql_select_db($mysql_database);
	#$myFile = "opensis-4.5-schema-mysql.sql";
	#$myFile = "opensis-4.7-schema-mysql.sql";
        $myFile = "opensis-4.8-schema-mysql.sql";
    executeSQL($myFile);
	
	//execute custome field for student
	foreach($objCustomStudents->customQueryString as $query){
	mysql_query($query);
	}
	//execute custome field for satff
	foreach($objCustomStaff->customQueryString as $query){
	mysql_query($query);
	}
	
	#$myFile = "opensis-4.5-procs-mysql.sql";
	$myFile = "opensis-4.8-procs-mysql.sql";
    executeSQL($myFile);
	mysql_query("ALTER TABLE USER_PROFILES CHANGE `id` `id` INT( 8 ) NOT NULL");
    $myFile = $Export_FileName;
    executeSQL($myFile);
	mysql_query("delete from APP");
	$appTable="INSERT INTO `APP` (`name`, `value`) VALUES
('version', '4.8'),
('date', 'February 01, 2011'),
('build', '02012011001'),
('update', '0'),
('last_updated', 'February 01, 2011')";
	mysql_query($appTable);
	$custom_insert=mysql_query("select count(*) from CUSTOM_FIELDS where title in('Ethnicity','Common Name','Physician','Physician Phone','Preferred Hospital','Gender','Email','Phone','Language')");
	$custom_insert=mysql_fetch_array($custom_insert);
	$custom_insert=$custom_insert[0];
	if($custom_insert<9){
	$custom_insert="INSERT INTO `CUSTOM_FIELDS` (`type`, `search`, `title`, `sort_order`, `select_options`, `category_id`, `system_field`, `required`, `default_selection`, `hide`) VALUES
('text', NULL, 'Ethnicity', 3, NULL, 1, 'Y', NULL, NULL, NULL),
('text', NULL, 'Common Name', 2, NULL, 1, 'Y', NULL, NULL, NULL),
('text', NULL, 'Physician', 6, NULL, 2, 'Y', NULL, NULL, NULL),
('text', NULL, 'Physician Phone', 7, NULL, 2, 'Y', NULL, NULL, NULL),
('text', NULL, 'Preferred Hospital', 8, NULL, 2, 'Y', NULL, NULL, NULL),
('text', NULL, 'Gender', 5, NULL, 1, 'Y', NULL, NULL, NULL),
('text', NULL, 'Email', 6, NULL, 1, 'Y', NULL, NULL, NULL),
('text', NULL, 'Phone', 9, NULL, 1, 'Y', NULL, NULL, NULL),
('text', NULL, 'Language', 8, NULL, 1, 'Y', NULL, NULL, NULL);";
mysql_query($custom_insert);
	}
	$login_msg=mysql_query("SELECT COUNT(*) FROM LOGIN_MESSAGE WHERE 1");
	$login_msg=mysql_fetch_array($login_msg);
	$login_msg=$login_msg[0];
	if($login_msg<1){
	$login_msg="INSERT INTO `LOGIN_MESSAGE` (`id`, `message`, `display`) VALUES
(1, 'This is a restricted network. Use of this network, its equipment, and resources is monitored at all times and requires explicit permission from the network administrator. If you do not have this permission in writing, you are violating the regulations of this network and can and will be prosecuted to the fullest extent of law. By continuing into this system, you are acknowledging that you are aware of and agree to these terms.', 'Y')";
mysql_query($login_msg);
	}
	
	$syear=mysql_fetch_assoc(mysql_query("select MAX(syear) as year from SCHOOLS"));
	$_SESSION['syear']=$syear['year'];
	mysql_query("UPDATE SCHEDULE SET dropped='Y' WHERE end_date IS NOT NULL AND end_date < CURDATE() AND dropped='N'");
	header('Location: step5.php');
	unset($objCustomStudents);
	unset($objCustomStaff);
}else{
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../styles/installer.css" />
</head>
<body>
<div class="heading2">Warning
<div style="height:270px;">
<br /><br />
 <table border="0" cellspacing="6" cellpadding="3" align="center">
            <tr>
			 <td colspan="2" align="center">
		<p>	The database you have chosen is not compliant with openSIS-CE ver 4.6. or ver 4.7 We are unable to proceed.</p>

<p>Click Retry to select another database, or Exit to quit the installation.
</p>
		</td>
			</tr>
            <tr>
            	<td colspan="2" style="height:100px;">&nbsp;</td>
            </tr>
			<tr>


  <td align="left"><a href="selectdb.php"><img src="images/retry.png"  alt="Retry"  border="0"/></a></td>
    <td align="right"><a href="step0.php" ><img src="images/exit.png" alt="Exit" border="0" /></a></td>


	          </tr>
	        </table>
</div>
</div>
</body>
</html>
<?php }

function backup_db($mysql_database,$Export_FileName){

			$print_form=0;
			//ob_start("ob_gzhandler");\
                       

			#header('Content-type: text/plain');
			#header('Content-Disposition: ; filename="'.$Export_FileName.'.sql"');
		 $writeString=_mysqldump($mysql_database); 
			//header("Content-Length: ".ob_get_length());
			//ob_end_flush();
			file_put_contents("$Export_FileName",$writeString);
		}
function _mysqldump($mysql_database)
{

		$writeString='';
        $sql="show tables where tables_in_$mysql_database not like 'COURSE_DETAILS%' and tables_in_$mysql_database not like 'ENROLL_GRADE%'
               and tables_in_$mysql_database not like 'MARKING_PERIODS%' and tables_in_$mysql_database not like 'TRANSCRIPT_GRADES%' ;";
	$result= mysql_query($sql);
	if( $result)
	{
		while( $row= mysql_fetch_row($result))
		{
			# _mysqldump_table_structure($row[0]);
		$writeString.= _mysqldump_table_data($row[0]);

		}
	
        }
	else
	{
		$writeString= "/* no tables in $mysql_database \n";
	}
	mysql_free_result($result);

        if(!$writeString){
         show_error1();
        }
        else
	return $writeString;
}

function _mysqldump_table_data($table)
{   $writeString='';
	$sql="select * from `$table`;";
	$result=mysql_query($sql);
	if( $result)
	{
		$num_rows= mysql_num_rows($result);
		$num_fields= mysql_num_fields($result);
        $numfields = mysql_num_fields($result);

		if( $num_rows> 0)
		{
			//echo "/* dumping data for table `$table` \n";

                        $writeString.= "--\n";
                        $writeString.= "-- Dumping data for table  `$table` \n";
                        $writeString.= "--\n";

			$field_type=array();
			$i=0;
			while( $i <$num_fields)
			{
				$meta= mysql_fetch_field($result, $i);
				array_push($field_type, $meta->type);
                                $colfields[] = mysql_field_name($result,$i);
				$i++;
			}
			//print_r( $field_type);
			$writeString.= "insert into `$table` (";
                        for($j=0; $j < $num_fields; $j++)
                        {
                            if($j==$num_fields-1)
                            $writeString.= $colfields[$j];
                        else
                        $writeString.= $colfields[$j].",";
                        }
                        $writeString.= ")values\n";
			$index=0;
			while( $row= mysql_fetch_row($result))
			{
				$writeString.= "(";
				for( $i=0; $i <$num_fields; $i++)
				{
					if( is_null( $row[$i]))
						$writeString.= "null";
					else
					{
						switch( $field_type[$i])
						{
							case 'int':
								$writeString.= $row[$i];
								break;
							case 'string':
							case 'blob' :
							default:
								$writeString.= "'".mysql_real_escape_string($row[$i])."'";
						}
					}
					if( $i <$num_fields-1)
						$writeString.= ",";
				}
				$writeString.= ")";
				if( $index <$num_rows-1)
					$writeString.= ",";
				else
					$writeString.= ";";
				$writeString.= "\n";
				$index++;
			}
		}
	}
	mysql_free_result($result);
	$writeString.= "\n";
        if(!$writeString){
         show_error1();
        }
        else
	return $writeString;
}
function executeSQL($myFile)
{	
	$sql = file_get_contents($myFile);
	$sqllines = explode("\n",$sql);
	$cmd = '';
	$delim = false;
	foreach($sqllines as $l)
	{
		if(preg_match('/^\s*--/',$l) == 0)
		{
			if(preg_match('/DELIMITER \$\$/',$l) != 0)
			{	
				$delim = true;
			}
			else
			{
				if(preg_match('/DELIMITER ;/',$l) != 0)
				{
					$delim = false;
				}
				else
				{
					if(preg_match('/END\$\$/',$l) != 0)
					{
						$cmd .= ' END';
					}
					else
					{
						$cmd .= ' ' . $l . "\n";
					}
				}
				if(preg_match('/.+;/',$l) != 0 && !$delim)
				{
					$result = mysql_query($cmd) or die(show_error1());
					$cmd = '';
				}
			}
		}
	}
}

function show_error1()
{
    $err .= "
<html>
<head>
<link rel='stylesheet' type='text/css' href='../styles/installer.css' />
</head>
<body>

<div style='height:280px;'>

<br /><br /><span class='header_txt'></span>

<div align='center'>
Username or Password or MySQL Configuration is incorrect
</div>
<div style='height:50px;'>&nbsp;</div>";
$err.="<div align='center'><a href='selectdb.php?mod=upgrade'><img src='images/retry.png' border='0' /></a> &nbsp; &nbsp; <a href='step0.php'><img src='images/exit.png' border='0' /></a></div>";
$err.="</div></body></html>";
echo $err;
}

?>
