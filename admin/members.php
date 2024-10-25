<?php
session_start();
Header("Content-Type: text/html; charset=UTF-8");
require_once "../include/config.php";
require_once "../include/convars.php";
if (!defined('_web_path')) {
	exit();
}

if (!isset($_SESSION["admin"]) || !isset($_SESSION["userlevel"]) || ($_SESSION["admin"] == "")) {
	session_unset();
	session_destroy();
	echo "<Script language=\"javascript\">window.location=\"login.php\"</script>";
	exit();
}
$admin = $_SESSION['admin'];
$userlevel = $_SESSION['userlevel'];

$_menu_id = 3;

if (isset($_GET["iRegister"])) {
	if ($_GET["iRegister"] == "1") {
		unset($_SESSION["u_code_1"]);
	}
}
if (isset($_POST["iRegister"])) {
	if ($_POST["iRegister"] == "1") {
		unset($_SESSION["u_code_1"]);
	}
}

$c_search_institution = 0;
if (isset($_POST["f_institution_id"])) {
	$c_search_institution = $_POST["f_institution_id"];
}
if (isset($_GET["f_institution_id"])) {
	$c_search_institution = $_GET["f_institution_id"];
}

include("../include/config_db.php");
?>
<!DOCTYPE html>
<html lang="th" class="no-js">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="KeyWords" content="">
	<meta name="Description" content="">
	<meta name="ROBOTS" content="index, follow">
	<title>ระบบคลังข้อมูลงานวิจัย <?php if (defined('__EC_NAME__')) {
										echo __EC_NAME__;
									} ?></title>
	<link href="../images/<?php if (defined('__EC_FAVICON__')) {
								echo __EC_FAVICON_ICO__;
							} ?>" rel="icon" type="image/ico">
	<link href="../images/<?php if (defined('__EC_FAVICON__')) {
								echo __EC_FAVICON__;
							} ?>" rel="icon" type="image/png" sizes="32x32">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css?v=<?php echo filemtime('../bootstrap/css/bootstrap.min.css'); ?>">
	<script src="../js/jquery.min.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="ajax.js"></script>
	<script type="text/javascript" src="ajax_content.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Prompt" rel="stylesheet">
	<link rel="stylesheet" href="../admin/style_admin.css?v=<?php echo filemtime('../admin/style_admin.css'); ?>">
	<SCRIPT LANGUAGE="JavaScript">
		$(window).scroll(function() {
			var sct = $(window).scrollTop();
			if (sct > 100) {
				document.getElementById('ontop1').classList.add("ontop1");
				document.getElementById('ontop2').classList.add("ontop2");
				document.getElementById('ontop3').classList.add("ontop3");
				document.getElementById('ontop4').classList.add("ontop4");
				document.getElementById('ontop5').classList.add("ontop5");
				document.getElementById('ontop6').classList.add("ontop6");
				document.getElementById('ontop7').classList.add("ontop7");
				document.getElementById('ontop8').classList.add("ontop8");
				document.getElementById('ontop9').classList.add("ontop9");
				document.getElementById('ontop10').classList.add("ontop10");
				document.getElementById('ontop11').classList.add("ontop11");
			} else {
				document.getElementById('ontop1').classList.remove("ontop1");
				document.getElementById('ontop2').classList.remove("ontop2");
				document.getElementById('ontop3').classList.remove("ontop3");
				document.getElementById('ontop4').classList.remove("ontop4");
				document.getElementById('ontop5').classList.remove("ontop5");
				document.getElementById('ontop6').classList.remove("ontop6");
				document.getElementById('ontop7').classList.remove("ontop7");
				document.getElementById('ontop8').classList.remove("ontop8");
				document.getElementById('ontop9').classList.remove("ontop9");
				document.getElementById('ontop10').classList.remove("ontop10");
				document.getElementById('ontop11').classList.remove("ontop11");
			}
		});
	</SCRIPT>
	<style>
		@media (min-width: 768px) {

			/*body {
			padding-top:50px;
		}*/
			table.floatThead-table {
				border-top: none;
				border-bottom: none;
			}

			th {
				position: sticky;
				top: 50px;
				background: #e5e5e5;
			}
		}

		.hSelect {
			padding-top: 10px;
			padding-bottom: 10px;
		}

		.ontop1 {
			z-index: 990;
		}

		.ontop2 {
			z-index: 991;
		}

		.ontop3 {
			z-index: 992;
		}

		.ontop4 {
			z-index: 993;
		}

		.ontop5 {
			z-index: 994;
		}

		.ontop6 {
			z-index: 995;
		}

		.ontop7 {
			z-index: 996;
		}

		.ontop8 {
			z-index: 997;
		}

		.ontop9 {
			z-index: 998;
		}

		.ontop10 {
			z-index: 999;
		}

		.ontop11 {
			z-index: 1000;
		}

		.col-lg-12,
		.col-lg-9,
		.col-lg-3 {
			margin: 0;
			padding: 0;
		}

		.col-md-12,
		.col-md-9,
		.col-md-3 {
			margin: 0;
			padding: 0;
		}

		.col-sm-12,
		.col-sm-9,
		.col-sm-3 {
			margin: 0;
			padding: 0;
		}
	</style>
</head>

<body role="document">
	<a href="#0" class="cd-top">Top</a>
	<!-- cd-top JS -->
	<script src="../js/main.js"></script>

	<div class="container-fluid">
		<?php require_once "header.php"; ?>
		<div class="row" style="padding-top:50px;padding-bottom:10px;">
			<div id="hSelect" class="hSelect">

				<form name="form2" method="post" action="members.php" onSubmit="return c_check2();" role="form">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
						<?php
						if (isset($_POST["c_code_1"])) {
							$c_code_1 = $_POST["c_code_1"];
						} else {
							$c_code_1 = "";
						}
						if (trim($c_code_1) != "") {
							$_SESSION["u_code_1"] = $c_code_1;
						} else {
							if (isset($_POST["c_code_1"])) {
								$_SESSION["u_code_1"] = "";
							}
						}
						?>
						<span class="sbreak1">
							<select name="f_institution_id" id="f_institution_id" class="form-control" style="width:200px;margin-top:2px;height:35px;">
								<option value="0">เลือกประเภทหน่วยงาน</option>
								<option value="1">หน่วยงานภาครัฐ/วิสาหกิจ</option>
								<option value="2">หน่วยงานภาคเอกชน</option>
								<option value="3">องค์กรอิสระ</option>
								<option value="4">สถาบันการศึกษาภาครัฐ</option>
								<option value="5">สถาบันการศึกษาเอกชน</option>
								<option value="6">ประชาชน</option>
								<option value="7">อื่น ๆ</option>
							</select>
						</span>
						<span class="sbreak2">
							<input type="text" name="c_code_1" id="c_code_1" class="searchbox-control" maxlength="30" placeholder="ข้อความที่ต้องการค้นหา" value="<?= isset($_SESSION["u_code_1"]) ? $_SESSION["u_code_1"] : ''; ?>">&nbsp; <input type="submit" name="Submit" id="Submit" value="ค้นหา" class="btn btn-success" style="width:70px; margin:2px;">&nbsp;<input type="button" name="Clear" id="Clear" value="ยกเลิก" class="btn btn-info" style="width:70px;margin-top:0;" onclick="window.location='members.php?iRegister=1';"></span>
					</div>
				</form>

			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="background-color:#<?php echo __EC_BGSHOW__; ?>;color:#<?php echo __EC_FONTSHOW__; ?>;">
				<h4 class="sub-header">สมาชิกที่ลงทะเบียน</h4>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

				<?php
				if (isset($_GET["sh_order"])) {
					$sh_order = $_GET["sh_order"];
				} else {
					$sh_order = 0;
				}
				if (!isset($_GET["Page"])) {
					if ($sh_order == 1) {
						$sh_order = 0;
					} else {
						$sh_order = 1;
					}
				}
				if ($sh_order == 1) {
					$asort = "<span class='glyphicon glyphicon-arrow-up' style='font-size:8px;'></span>";
				} else {
					$asort = "<span class='glyphicon glyphicon-arrow-down' style='font-size:8px;'></span>";
				}
				if (isset($_GET["sd"])) {
					$sd = $_GET["sd"];
				} else {
					$sd = 0;
				}
				$a1sort = $a2sort = $a3sort = $a4sort = $a5sort = $a6sort = $a7sort = "<span class='glyphicon glyphicon-sort' style='font-size:8px;'></span>";
				switch ($sd) {
					case '1':
						$a1sort = $asort;
						break;
					case '2':
						$a2sort = $asort;
						break;
					case '3':
						$a3sort = $asort;
						break;
					case '4':
						$a4sort = $asort;
						break;
					case '5':
						$a5sort = $asort;
						break;
					case '6':
						$a6sort = $asort;
						break;
					case '7':
						$a7sort = $asort;
						break;
				}
				?>

				<div id="tblResponsive">

					<table class="table table-striped table-bordered sticky-header" id="table-1" style="background: url('../images/<?php if (defined('__EC_PICHOME__')) {
																																		echo __EC_PICHOME__;
																																	} ?>') repeat-y; background-attachment:fixed; background-size:contain;height:100%;width:100%;">
						<thead>
							<tr id="ontop1" >
								<th id="ontop2">&nbsp;</th>
								<th id="ontop3">&nbsp;</th>
								<th style="vertical-align:middle;text-align:center;" id="ontop4"><a href="members.php?sd=1&sh_order=<?= $sh_order; ?>" target="_parent" style="white-space: nowrap;">ID <?= $a1sort; ?></a></th>
								<th style="vertical-align:middle;text-align:center;" id="ontop5"><a href="members.php?sd=2&sh_order=<?= $sh_order; ?>" target="_parent" style="white-space: nowrap;">ชื่อ-นามสกุล <?= $a2sort; ?></a></th>
								<th style="vertical-align:middle;text-align:center;" id="ontop6"><a href="members.php?sd=3&sh_order=<?= $sh_order; ?>" target="_parent" style="white-space: nowrap;">ชื่อ login <?= $a3sort; ?></a></th>
								<th style="vertical-align:middle;text-align:center;" id="ontop7"><a href="members.php?sd=4&sh_order=<?= $sh_order; ?>" target="_parent" style="white-space: nowrap;">หน่วยงานที่สังกัด <?= $a4sort; ?></a></th>
								<th style="vertical-align:middle;text-align:center;" id="ontop8"><a href="members.php?sd=5&sh_order=<?= $sh_order; ?>" target="_parent" style="white-space: nowrap;">ประเภทหน่วยงาน <?= $a5sort; ?></a></th>
								<th style="vertical-align:middle;text-align:center;" id="ontop9">โทรศัพท์</th>
								<th style="vertical-align:middle;text-align:center;" id="ontop10">อีเมล</th>
								<th style="vertical-align:middle;text-align:center;" id="ontop11">ที่อยู่</th>
							</tr>
						</thead>
						<tbody class="bgw">
							<?php
							$sql = "select * From `ers_member` where 1 ";
							if (isset($_SESSION["u_code_1"]) and !empty($_SESSION["u_code_1"])) {
								$u_code_1 = $_SESSION["u_code_1"];
								$u_code_1 = trim($u_code_1);
								$sql .= "and ((`id` = '$u_code_1')";
								if (strpos($u_code_1, " ") >= 1) {
									$u_code_array = explode(" ", $u_code_1);
									$u_search = $u_code_array[0];
									$sql .= " or (`em_username` like '%$u_search%') or (`em_title` like '%$u_search%') or (`em_firstname` like '%$u_search%') or (`em_lastname` like '%$u_search%') or (`em_institution` like '%$u_search%') or (`em_institution_other` like '%$u_search%') or (`em_phone` like '%$u_search%') or (`em_email` like '%$u_search%') or (`em_address` like '%$u_search%')";
									for ($i = 1, $size = count($u_code_array); $i < $size; ++$i) {
										$u_search = $u_code_array[$i];
										$sql .= ") and ((`em_username` like '%$u_search%') or (`em_title` like '%$u_search%') or (`em_firstname` like '%$u_search%') or (`em_lastname` like '%$u_search%') or (`em_institution` like '%$u_search%') or (`em_institution_other` like '%$u_search%') or (`em_phone` like '%$u_search%') or (`em_email` like '%$u_search%') or (`em_address` like '%$u_search%')";
									}
								} else {
									$sql .= " or (`em_username` like '%$u_code_1%') or (`em_title` like '%$u_code_1%') or (`em_firstname` like '%$u_code_1%') or (`em_lastname` like '%$u_code_1%') or (`em_institution` like '%$u_code_1%') or (`em_institution_other` like '%$u_code_1%') or (`em_phone` like '%$u_code_1%') or (`em_email` like '%$u_code_1%') or (`em_address` like '%$u_code_1%')";
								}
								$sql .= ") ";
							}
							if ($c_search_institution > 0) {
								$sql .= "and (`em_institution_type` = '" . $c_search_institution . "') ";
							}

							if (isset($_GET["sd"])) {
								$sd = $_GET["sd"];
							} else {
								$sd = 0;
							}
							switch ($sd) {
								case '1':
									$sql .= "Order by `id` ";
									break;
								case '2':
									$sql .= "Order by `em_firstname` ";
									break;
								case '3':
									$sql .= "Order by `em_username` ";
									break;
								case '4':
									$sql .= "Order by `em_institution` ";
									break;
								case '5':
									$sql .= "Order by `em_institution_type` ";
									break;
								default:
									$sql .= "Order by `id` ";
							}
							if ($sh_order == 1) {
								$sql .= "DESC ";
							} else {
								$sql .= "ASC ";
							}
							$ch = array("2", "3", "4", "5");
							if (in_array($sd, $ch)) {
								$sql .= ",`id` Desc ";
							}
							$res = $mysqli->query($sql);
							$totalRows = $res->num_rows;

							if (isset($_POST["Per_Page"])) {
								$Per_Page = $_POST["Per_Page"];
							} else {
								$Per_Page = $_GET["Per_Page"];
							}
							if (!isset($Per_Page) or empty($Per_Page)) {
								$Per_Page = 18;
							}
							$Page = $_GET["Page"];
							if (!$_GET["Page"]) {
								$Page = 1;
							}

							$Page_Start = (($Per_Page * $Page) - $Per_Page);
							if ($totalRows <= $Per_Page) {
								$Num_Pages = 1;
							} else if (($totalRows % $Per_Page) == 0) {
								$Num_Pages = ($totalRows / $Per_Page);
							} else {
								$Num_Pages = ($totalRows / $Per_Page) + 1;
								$Num_Pages = (int)$Num_Pages;
							}
							if (!($Page_Start)) {
								$Page_Start = 0;
							}

							$sql .= " LIMIT $Page_Start,$Per_Page";
							$res = $mysqli->query($sql);

							if ($totalRows > 0) {

								if ($Page == 1) {
									$jk = 0;
								} else {
									$a = $Page * $Per_Page;
									$jk = $a - $Per_Page;
								}

								while ($row = $res->fetch_assoc()) {
									$jk++;
									$c_id = $row["id"];
									$c_username = $row["em_username"];
									$c_name = $row["em_title"];
									$c_name .= $row["em_firstname"];
									$c_name .= " " . $row["em_lastname"];
									$c_gender = $row["em_gender"];
									$c_institution = $row["em_institution"];
									$c_institution_type = $row["em_institution_type"];
									$c_institution_other = $row["em_institution_other"];
									$c_phone = $row["em_phone"];
									$c_email = $row["em_email"];
									$c_address = $row["em_address"];
									$adrlen = mb_strlen($c_address, 'UTF-8');
									$adrlen2 = 70;
									$c_address2 = $c_address;
									if ($adrlen > $adrlen2) {
										$c_address2 = mb_substr($c_address, 0, $adrlen2) . "..";
									}
									$c_institution_name = "";
									switch ($c_institution_type) {
										case '1':
											$c_institution_name = "หน่วยงานภาครัฐ/วิสาหกิจ";
											break;
										case '2':
											$c_institution_name = "หน่วยงานภาคเอกชน";
											break;
										case '3':
											$c_institution_name = "องค์กรอิสระ";
											break;
										case '4':
											$c_institution_name = "สถาบันการศึกษาภาครัฐ";
											break;
										case '5':
											$c_institution_name = "สถาบันการศึกษาเอกชน";
											break;
										case '6':
											$c_institution_name = "ประชาชน";
											break;
										case '7':
											$c_institution_name = $c_institution_other;
											break;
										default:
											$c_institution_name = "";
									}
									$code_1 = $c_name;
									$bcolor = "rgba(255, 255, 255, 0.8)";
									if (($jk % 2) == 0) {
										$bcolor = "rgba(236, 240, 241, 0.8)";
									}

							?>
									<tr>
										<td style="text-align:center;min-width:130px;">
											<?php
											echo "<a href='javascript:void(0)' onclick='openWindow($c_id)' style='color:green;font-size:16px;' title='แก้ไขรหัสผ่าน'><span class='glyphicon glyphicon-edit'></span>&nbsp;<span style='font-size:14px;'>เปลี่ยนรหัสผ่าน</span></a>";
											?>
										</td>
										<td style="text-align:center;min-width:100px;">
											<?php
											echo "<a href='javascript:void(0)' style='color:red;font-size:16px;' title='ลบ' onclick=\"javascript:confirmDelete('dfile$jk','" . $c_id . "','" . $code_1 . "','1');\"><span class='glyphicon glyphicon-trash'></span>&nbsp;<span style='font-size:14px;'>ลบ</span></a><span id='dfile$jk'></span>";
											?>
										</td>
										<td style="text-align:center;padding-left:0;padding-right:0;">&nbsp;<?= $c_id; ?>&nbsp;</td>
										<td style="text-align:left;padding-left:0;padding-right:0;white-space: nowrap;">&nbsp;<?= $c_name; ?>&nbsp;</td>
										<td style="text-align:center;padding-left:0;padding-right:0;white-space: nowrap;">&nbsp;<?= $c_username; ?>&nbsp;</td>
										<td style="text-align:left;padding-left:0;padding-right:0;white-space: nowrap;">&nbsp;<?= $c_institution; ?>&nbsp;</td>
										<td style="text-align:left;padding-left:0;padding-right:0;white-space: nowrap;">&nbsp;<?= $c_institution_name; ?>&nbsp;</td>
										<td style="text-align:center;padding-left:0;padding-right:0;white-space: nowrap;">&nbsp;<a href='tel:<?= $c_phone; ?>' target="_blank" title='<?= $c_phone; ?>'><?= $c_phone; ?></a>&nbsp;</td>
										<td style="text-align:center;padding-left:0;padding-right:0;white-space: nowrap;">&nbsp;<a href='mailto:<?= $c_email; ?>' target='_blank' title="<?= $c_email; ?>"><?= $c_email; ?></a>&nbsp;</td>
										<td style="text-align:left;">
											<div style="margin-left:5px;margin-right:5px;"><?= $c_address; ?></span>
												
										</td>
									</tr>
							<?php
								} //while
								$res->free();
							} else {
								echo "<tr class='text-center' style='background-color:#ffffff'><td colspan='10'><br><span style='color:#ff0000;font-size:18px;'>..ไม่พบข้อมูล..</span></td></tr>";
							}
							?>
						</tbody>
					</table>
				</div>

			</div>
		</div>

		<div class="row" style="margin:0;padding:0;">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-7" style="font-size:14px;">หน้า :
					<?php
					$pages = new Paginator;
					$pages->items_total = $totalRows;
					$pages->mid_range = 7;
					$pages->current_page = $Page;
					$pages->default_ipp = $Per_Page;
					$pages->url_next = $_SERVER["PHP_SELF"] . "?&f_institution_id=$c_search_institution&sd=$sd&sh_order=$sh_order&Page=";
					$pages->paginate();
					echo $pages->display_pages();
					unset($Paginator);
					?>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-5 text-right">
					<form name="form3" method="post" action="members.php?sd=<?= $sd; ?>&sh_order=<?= $sh_order; ?>&Page=1" role="form">
						<select name="Per_Page" id="Per_Page" style="width:130px;border-radius:5px;border:1px solid #<?php echo __EC_BGSHOW__; ?>;padding:5px;" onchange="document.form3.submit()">
							<option value="18" <?php if ($Per_Page == '18') {
													echo "selected";
												} ?>>18 รายการ/หน้า</option>
							<option value="30" <?php if ($Per_Page == '30') {
													echo "selected";
												} ?>>30 รายการ/หน้า</option>
							<option value="60" <?php if ($Per_Page == '60') {
													echo "selected";
												} ?>>60 รายการ/หน้า</option>
							<option value="90" <?php if ($Per_Page == '90') {
													echo "selected";
												} ?>>90 รายการ/หน้า</option>
						</select>
						<input type="hidden" name="f_institution_id" id="f_institution_id" value="<?php if ($c_search_institution) {
																										echo $c_search_institution;
																									} else {
																										echo '';
																									} ?>">
					</form>
				</div>
			</div>

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div align="center" style="padding-bottom:<?php if ($Per_Page == '18') {
																echo "10px";
															} else {
																echo "80px";
															} ?>;"><?php require_once "./footer.php"; ?></div>
			</div>
		</div><!-- /.row -->

	</div>
	<div id="ersshow"></div>
</body>

</html>

<script>
	function openWindow(idp) {
		var url = 'changepwmb.php?mb_id=' + idp;
		var sw = document.body.clientWidth;
		var sh = screen.height;
		var sw3 = 0;
		if (sw < 769) {
			sw = (sw * 80) / 100;
			sh = (sh * 60) / 100;
		} else {
			sw = (sw * 30) / 100;
			sh = (sh * 40) / 100;
		}
		sw3 = sw + 10;

		var myleft = (document.body.clientWidth) ? (document.body.clientWidth - sw3) / 2 : 100;
		var sct = $(window).scrollTop();
		var left = (myleft) + "px";
		var tops = (sct + 25) + "px";
		var win = window.open(url, 'staff', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,,width=' + sw + ', height=' + sh + ', top=' + tops + ', left=' + left);
		win.focus();
	}

	function confirmDelete(span_id, id_member, filename, content_id) {
		console.log('id_member', id_member)
		if (confirm("ยืนยันการลบข้อมูล " + filename)) {
			ajax_loadContent(span_id, 'del_member.php', id_member, 'member', content_id);
			// window.location.reload(true);
			/*window.location.replace("members.php?iRegister=1");*/
		}
	}
	document.getElementById("fmnavbar").className = "navbar navbar-default navbar-fixed-top";
	if (document.body.clientWidth > 767) {
		document.getElementById('tblResponsive').classList.remove("table-responsive");
		/*$(document).ready(function(){
		  $(".sticky-header").floatThead({top:50});
		});*/
	} else {
		document.getElementById('tblResponsive').classList.add("table-responsive");
		document.getElementById('table-1').classList.remove("sticky-header");
	}
	document.getElementById('c_code_1').focus();
</script>