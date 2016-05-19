<?php
session_start();

//memoria cimek//
$red_address = "0x78";
$green_address = "0x79";
$blue_address = "0x80";
$sw_address = "0x81";
$canvas_up_address = "0x82";
$canvas_down_address = "0x83";

//Memoria nyitasa csak olvasasra tartalomellenorzes vegett//
@$redRead = shmop_open($red_address, "a", 0666, 0);
@$greenRead = shmop_open($green_address, "a", 0666, 0);
@$blueRead = shmop_open($blue_address, "a", 0666, 0);
@$swRead = shmop_open($sw_address, "a", 0666, 0);
@$canvasUpRead = shmop_open($canvas_up_address, "a", 0666, 0);
@$canvasDownRead = shmop_open($canvas_down_address, "a", 0666, 0);

//ha valamelyik ures, akkor inicializalom az alap ertekre//
if (empty($redRead) OR empty($greenRead) OR empty($blueRead) OR empty($swRead) OR empty($canvasUpRead) OR empty($canvasDownRead)) {
//az olvasasra megnyitott memoriak lezarasa//
shmop_close($redRead);
shmop_close($greenRead);
shmop_close($blueRead);
shmop_close($swRead);
shmop_close($canvasUpRead);
shmop_close($canvasDownRead);
    
$r_memory = shmop_open($red_address, "c", 0644, 100);
$g_memory = shmop_open($green_address, "c", 0644, 100);
$b_memory = shmop_open($blue_address, "c", 0644, 100);
$s_memory = shmop_open($sw_address, "c", 0644, 100);
$cu_memory = shmop_open($canvas_up_address, "c", 0644, 100);
$cd_memory = shmop_open($canvas_down_address, "c", 0644, 100);

//memoriaba iras az inicializalashoz//
shmop_write($r_memory,"0", 0);
print "<p>Piros memoria OK.</p>";
shmop_write($g_memory,"0", 0);
print "<br><p>Zold memoria OK.</p>";
shmop_write($b_memory,"0", 0);
print "<br><p>Kek memoria OK.</p>";
shmop_write($s_memory,"0", 0);
print "<br><p>KP memoria OK.</p>";
shmop_write($cu_memory,"0", 0);
print "<br><p>Vaszon fel memoria OK.</p>";
shmop_write($cd_memory,"0", 0);
print "<br><p>Vaszon le memoria OK.</p>";

$sh = shell_exec('nohup sudo python /home/pi/Desktop/Adafruit_PWM_Servo_Driver/led.py > /dev/null 2>&1 &');

print "<br><p>Python script elinditva</p>";
}	else
{
print "<p>Minden memoriacim letezik</p><br>";
}
    //Adatbeerkezes eseten memoria irasa
if( isset($_POST['voros'] ) && $_SESSION['red'] != $_POST['voros'])	{
 $_SESSION['red'] = $_POST['voros'];
$r_memory = shmop_open($red_address, "w", 0644, 100);
 shmop_write($r_memory, $_SESSION['red'] . "r", 0);
shmop_close($r_memory);
}

    //Adatbeerkezes eseten memoria irasa
if( isset($_POST['zld'] ) && $_SESSION['green'] != $_POST['zld'])  {
 $_SESSION['green'] = $_POST['zld'];
$g_memory = shmop_open($green_address, "w", 0644, 100);
 shmop_write($g_memory, $_SESSION['green'] . "g", 0);
shmop_close($g_memory);
}
    
    //Adatbeerkezes eseten memoria irasa
if( isset($_POST['blu'] ) && $_SESSION['blue'] != $_POST['blu'])  {
 $_SESSION['blue'] = $_POST['blu'];
$b_memory = shmop_open($blue_address, "w", 0644, 100);
 shmop_write($b_memory, $_SESSION['blue'] . "b", 0);
shmop_close($b_memory);
}

if( isset($_POST['kapcs'] ) && $_SESSION['sw'] != $_POST['kapcs'])  {
 $_SESSION['sw'] = $_POST['kapcs'];
$s_memory = shmop_open($sw_address, "w", 0644, 100);
 shmop_write($s_memory, $_SESSION['sw'] . "s", 0);
shmop_close($s_memory);
}

if( isset($_POST['vaszonfel'] ) && $_SESSION['vf'] != $_POST['vaszonfel'])  {
 $_SESSION['vf'] = $_POST['vaszonfel'];
$cu_memory = shmop_open($canvas_up_address, "w", 0644, 100);
 shmop_write($cu_memory, $_SESSION['vf'] . "vf", 0);
shmop_close($cu_memory);
}

if( isset($_POST['vaszonle'] ) && $_SESSION['vl'] != $_POST['vaszonle'])  {
 $_SESSION['vl'] = $_POST['vaszonle'];
$cd_memory = shmop_open($canvas_down_address, "w", 0644, 100);
 shmop_write($cd_memory, $_SESSION['vl'] . "vl", 0);
shmop_close($cd_memory);
}

print "<br><br><h1>Es ez nekunk milyen jo!</h1>";

?>
