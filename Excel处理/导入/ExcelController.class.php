<?php

namespace Manage\Controller;
use Think\Controller;
class ExcelController extends BaseController {
	//导入人员基本信息	
	//header("Content-type: text/html; charset=utf-8");
	public function toPeople(){
		  header("Content-type: text/html; charset=utf-8");
			layout(false);
			    
		if(IS_POST){
			if (!empty($_FILES['file_excel']['name'] )){
				$tmp_file = $_FILES ['file_excel']['tmp_name'];
				$file_types = explode ( ".", $_FILES ['file_excel'] ['name'] );
				$file_type = $file_types [count($file_types) - 1];
				/*判别是不是.xls文件，判别是不是excel文件*/
				if (strtolower ( $file_type ) != "xls")
				{
					$this->error ( '不是Excel文件，重新上传' );
				}
				/*设置上传路径*/
				$savePath = './Upload/';
				/*以时间来命名上传的文件*/
				$str = date ( 'Ymdhis' );
				$file_name = $str . "." . $file_type;
				/*是否上传成功*/
				if (!copy( $tmp_file, $savePath . $file_name ))
				{
				$this->error ( '上传失败' );
				}
				/*
				*对上传的Excel数据进行处理生成编程数据,这个函数会在下面第三步的ExcelToArray类中
				注意：这里调用执行了第三步类里面的read函数，把Excel转化为数组并返回给$res,再进行数据库写入
				*/
				$res = excelRead( $savePath . $file_name );
				/*
				重要代码 解决Thinkphp M、D方法不能调用的问题
				如果在thinkphp中遇到M 、D方法失效时就加入下面一句代码
				*/
				//spl_autoload_register ( array ('Think', 'autoload' ) );
				/*对生成的数组进行数据库的写入*/
				//dump($res);return;
				$countrecord = count($res)-2;
				$errorrecord = 0;
				$apprecord = 0;
				$updaterecord = 0;
				foreach ( $res as $k => $v ){
					if ($k > 2){ //第1行为注释，第2行为表头
						$data ['filenum'] = $v [0];
						$data ['name'] = $v [1];
						$data ['idnum'] = $v [2];
						$data ['workplace'] = $v [3];
						$data ['gender'] = $v [4];
						$data ['major'] = $v [5];
						$data ['leval'] = $v [6];
						$data ['phone'] = $v [7];
						$data ['culture'] = $v [8];
						//$tempTime1 = strtotime($v[7]);
						//$data ['regtime'] = date("Y-m-d H:i:s",$tempTime1);  以填写的时间为准改为以当前时间
						$data ['regtime'] = date("Y-m-d H:i:s");
						
						$data ['state'] = '正常';
						$data ['file_state'] = $v [9];
						$data ['batch'] = $v [10];
						$data ['b_num'] = $v [11];
						$tempTime2 = strtotime($v [12]);
						$data ['check_time'] = date("Y-m-d H:i:s",$tempTime2);
						$data ['file_pay'] = $v [13];
						$data ['check_type'] = $v [14];
						$data ['file_year_num'] = $v [15];//如：20142015
						$peopleTempId = checkIdnum($v[2]);
						$checkjx = checkjxjy($v[0]);
						//var_dump($peopleTempId);
						//var_dump($checkjx);die ;
						if($peopleTempId && $checkjx){
							        $pid = $peopleTempId['id'];
							        $jxjyid = $checkjx['id'];
							        if($pid == $jxjyid)
									     {
									     $result = M('People')->where('id='.$pid)->save($data);
									     $updaterecord++;
									     echo $v[1]."　　　　<span style=\"color:green;\">证书与身份证号重复，更新成功</span>";
									     echo "<br/>";
									     echo "<br/>";
							              }
							             else
								             {
								           $errorrecord++;
								            echo $v[1]."　　　　<span style=\"color:#ff0000;\">两者都有证书管理号与身份证号对应不上，失败</span>";
								            echo "<br/>";
								            echo "<br/>";	
								             }
						    }elseif ($peopleTempId)
						              {
                            	           $errorrecord++;
								            echo $v[1]."　　　　<span style=\"color:#ff0000;\">只有身份证号但没有对应的证书管理号，失败</span>";
								            echo "<br/>";
								            echo "<br/>";
								
								       }elseif ($checkjx)
								   {
									       $errorrecord++;
								            echo $v[1]."　　　　<span style=\"color:#ff0000;\">只有证书管理号但没有对应的身份证号，失败</span>";
								            echo "<br/>";
								            echo "<br/>";
								    }else 
									     {
										
									$result = M('People')->add($data);
									$apprecord++;
									echo $v[1]."　　　　<span style=\"color:blue;\">添加人员成功！</span>";
									echo "<br/>";
									echo "<br/>";	
									      }
									
						}
				}
				
				      echo '共有记录：'.$countrecord.',   更新记录：'.$updaterecord.',  新加记录：'.$apprecord.",  失败记录：".$errorrecord;
			//$this->success("导入完成");
		
			}
		}else{
			$this->display();
		}
	}
	
	//导入学习课程信息，需要人员基本信息作为前导步骤用来匹配人员ID编号，如果匹配不到则归于id=0
	public function toRecord(){
		    header("Content-type: text/html; charset=utf-8");
			layout(false);
	
		if(IS_POST){
			if (!empty($_FILES['file_excel']['name'] )){
				$tmp_file = $_FILES ['file_excel']['tmp_name'];
				
				$file_types = explode ( ".", $_FILES ['file_excel'] ['name'] );
				$file_type = $file_types [count($file_types) - 1];
				/*判别是不是.xls文件，判别是不是excel文件*/
				if (strtolower ( $file_type ) != "xls")
				{
					$this->error ( '不是Excel文件，重新上传' );
				}
				/*设置上传路径*/
				$savePath = './Upload/';
				/*以时间来命名上传的文件*/
				$str = date ( 'Ymdhis' );
				$file_name = $str . "." . $file_type;
				/*是否上传成功*/
				if (!copy( $tmp_file, $savePath . $file_name ))
				{
				$this->error ( '上传失败' );
				}
				/*
				*对上传的Excel数据进行处理生成编程数据,这个函数会在下面第三步的ExcelToArray类中
				注意：这里调用执行了第三步类里面的read函数，把Excel转化为数组并返回给$res,再进行数据库写入
				*/
				$res = excelRead( $savePath . $file_name );
				/*
				重要代码 解决Thinkphp M、D方法不能调用的问题
				如果在thinkphp中遇到M 、D方法失效时就加入下面一句代码
				*/
				//spl_autoload_register ( array ('Think', 'autoload' ) );
				/*对生成的数组进行数据库的写入*/
				//dump($res);return;
				$countrecord = count($res)-2;
				$errorrecord = 0;
				$apprecord = 0;
				$updaterecord = 0;
				$peopleModel = M('People');
				foreach ( $res as $k => $v ){
					if ($k > 2){ //第1行为注释，第2行为表头
						$peopleId = $peopleModel->where("idnum='".$v [1]."'")->getField('id');
						
						if($peopleId){
							$data ['people_id'] = $peopleId;
							//echo $peopleId;return;
						}else{
							$data ['people_id'] = 0;
						//	$this->toLog('第'.$k.'行有错误，没有找到对应的人员基本信息，导入数据库失败');
							$errorrecord++;
							echo $v[0]."　　　　<span style=\"color:#ff0000;\">没有找到对应的人员基本信息，导入数据库失败</span>";
							echo "<br/>";
							echo "<br/>";
							//return;
							continue;
						}
						$data ['name'] = $v [2];
						$data ['money'] = $v [3];
						$data ['year'] = $v [4];
						//$tempTime1 = strtotime($v[5]);
						//$data ['time'] = date("Y-m-d H:i:s",$tempTime1);
						$data ['time'] = date("Y-m-d H:i:s");
						//$data ['state'] = $v [6];
						$data ['state'] = '正常';
						//$this->updataPeopleBatchAndBnumByIdnum($v[1],$v[7],$v[8]);
						
						$recordTempId = checkRecord($v [1], $v [4]);
						if($recordTempId){
							$result = M('Record')->where('id='.$recordTempId)->save($data);
						//	$this->toLog('第：'.$k.'行有重复记录，更新信息');
							//echo $v[0]."　　重复记录，更新信息成功";
							$updaterecord++;
							echo $v[0]."　　　　<span style=\"color:green;\">重复记录，更新信息成功</span>";
							echo "<br/>";
							echo "<br/>";
							if ($result === false){
							//	$this->toLog('第：'.$k.'行有重复记录，更新信息失败');
								echo $v[0]."　　　　<span style=\"color:#ff0000;\">重复记录，更新失败</span>";
								//echo $v[0]."　　重复记录，更新失败";
								echo "<br/>";
							    echo "<br/>";
								
								//return;
							}
						}else{
							$result = M('Record')->add($data);
							if ($result === false){
							//	$this->toLog('第：'.$k.'行有记录错误，添加失败');
						        echo $v[0]."　　　　<span style=\"color:#ff0000;\">记录错误，添加失败</span>";
								//echo $v[0]."　　记录错误，添加失败";
								echo "<br/>";
						    	echo "<br/>";
						 		//return;
							}else{
						$apprecord++;
					           echo $v[0]."　　　　<span style=\"color:blue;\">记录添加成功</span>";
								//echo $v[0]."　　记录添加成功";
								echo "<br/>";
						    	echo "<br/>";
						}
						}
						//echo $peopleModel->_sql().'<br>';
					}

				} 
          echo '共有记录：'.$countrecord.',   更新记录：'.$updaterecord.',  新加记录：'.$apprecord.",  失败记录：".$errorrecord;
			//$this->success("导入完成");
			}
		}else{
			$this->display();
		}
	}
    
	//导入审核文件
	public function toFile(){
		  header("Content-type: text/html; charset=utf-8");
			layout(false);
			    
		if(IS_POST){
			if (!empty($_FILES['file_excel']['name'] )){
				$tmp_file = $_FILES ['file_excel']['tmp_name'];
				
				$file_types = explode ( ".", $_FILES ['file_excel'] ['name'] );
				$file_type = $file_types [count($file_types) - 1];
				/*判别是不是.xls文件，判别是不是excel文件*/
				if (strtolower ( $file_type ) != "xls")
				{
					$this->error ( '不是Excel文件，重新上传' );
				}
				/*设置上传路径*/
				$savePath = './Upload/';
				/*以时间来命名上传的文件*/
				$str = date ( 'Ymdhis' );
				$file_name = $str . "." . $file_type;
				/*是否上传成功*/
				if (!copy( $tmp_file, $savePath . $file_name ))
				{
				$this->error ( '上传失败' );
				}
				/*
				*对上传的Excel数据进行处理生成编程数据,这个函数会在下面第三步的ExcelToArray类中
				注意：这里调用执行了第三步类里面的read函数，把Excel转化为数组并返回给$res,再进行数据库写入
				*/
				$res = excelRead( $savePath . $file_name );
				/*
				重要代码 解决Thinkphp M、D方法不能调用的问题
				如果在thinkphp中遇到M 、D方法失效时就加入下面一句代码
				*/
				//spl_autoload_register ( array ('Think', 'autoload' ) );
				/*对生成的数组进行数据库的写入*/
				//dump($res);return;
				$peopleModel = M('People');
				$countrecord = count($res)-2;
				$errorrecord = 0;
				$apprecord = 0;
				$updaterecord = 0;
				$i = 0;
				foreach ( $res as $k => $v ){
					if ($k > 2){ //第1行为注释，第2行为表头
					$i++;
						$peopleId = $peopleModel->where("idnum='".$v [1]."'")->getField('id');
						if($peopleId){
							$data ['people_id'] = $peopleId;
						}else{
							$data ['people_id'] = 0;
									$errorrecord++;
							echo $i."　　　　<span style=\"color:#ff0000;\">没有找到对应的人员基本信息，导入数据失败</span>";
							echo "<br/>";
							echo "<br/>";
							//$this->toLog('第'.$k.'行有错误，没有找到对应的人员基本信息，导入数据库失败');
							//return;
							continue;
						}
						$data ['name'] = $v [2];
						$data ['year'] = $v [3];
						//$tempTime1 = strtotime($v[4]);
						$data ['time'] = date("Y-m-d H:i:s",$tempTime1);
						//$data ['state'] = $v [5];
						$data ['state'] = '正常';
						$this->updataPeopleBatchAndBnumByIdnum($v[1],$v[4],$v[5]);
						$this->updataPeopleFileYearNumByIdnum($v[1],$v[6],$v[7]);
						$fileTempId = checkFile($v [1], $v [3]);
						if($fileTempId){
							//$data['id'] = (int)$fileTempId;
							//dump($data);
							$result = M('File')->where('id='.$fileTempId)->save($data);
							$updaterecord++;
							echo $i."　　　　<span style=\"color:green;\">重复记录，更新信息成功</span>";
							echo "<br/>";
							echo "<br/>";
							//$this->toLog('第：'.$k.'行有重复记录，更新信息');
							if ($result === false){
							//	$this->toLog('第'.$k.'行有错误，导入数据库失败1 ');
								//return;
							}
						}else{
							$result = M('File')->add($data);
							if ($result === false){
									$errorrecord++;
							echo $i."　　　　<span style=\"color:#ff0000;\">导入数据失败</span>";
							echo "<br/>";
							echo "<br/>";
								//$this->toLog('第'.$k.'行有错误，导入数据库失败2');
								//return;
							}else
								{
								$apprecord++;
					           echo $i."　　　　<span style=\"color:blue;\">记录添加成功</span>";
								//echo $v[0]."　　记录添加成功";
								echo "<br/>";
						    	echo "<br/>";	
									
								}
						}
					}
				}
     echo '共有记录：'.$countrecord.',   更新记录：'.$updaterecord.',  新加记录：'.$apprecord.",  失败记录：".$errorrecord;
			       //$this->success("导入完成");
			}
		}else{
			$this->display();
		}
	}
	
	//导入未领证书人员信息
	public function toWeiLingPeople(){
		if(IS_POST){
			if (!empty($_FILES['file_excel']['name'] )){
				$tmp_file = $_FILES ['file_excel']['tmp_name'];
				$file_types = explode ( ".", $_FILES ['file_excel'] ['name'] );
				$file_type = $file_types [count($file_types) - 1];
				/*判别是不是.xls文件，判别是不是excel文件*/
				if (strtolower ( $file_type ) != "xls")
				{
					$this->error ( '不是Excel文件，重新上传' );
				}
				/*设置上传路径*/
				$savePath = './Upload/';
				/*以时间来命名上传的文件*/
				$str = date ( 'Ymdhis' );
				$file_name = $str . "." . $file_type;
				/*是否上传成功*/
				if (!copy( $tmp_file, $savePath . $file_name ))
				{
				$this->error ( '上传失败' );
				}
				/*
				*对上传的Excel数据进行处理生成编程数据,这个函数会在下面第三步的ExcelToArray类中
				注意：这里调用执行了第三步类里面的read函数，把Excel转化为数组并返回给$res,再进行数据库写入
				*/
				$res = excelRead( $savePath . $file_name );
				/*
				重要代码 解决Thinkphp M、D方法不能调用的问题
				如果在thinkphp中遇到M 、D方法失效时就加入下面一句代码
				*/
				//spl_autoload_register ( array ('Think', 'autoload' ) );
				/*对生成的数组进行数据库的写入*/
				//dump($res);return;
				$peopleModel = M('People');
				foreach ( $res as $k => $v ){
					if ($k > 2){ //第1行为注释，第2行为表头
						$data ['batch'] = $v [2];
						$data ['state'] = "审核中";
						$data ['b_num'] = $v [4];
						$result = M('People')->where('idnum='.$v [0])->save($data);
					}
				}
				$this->success("导入完成");
			}
		}else{
			$this->display();
		}
	}


	public function tocomputer(){
		    header("Content-type: text/html; charset=utf-8");
			layout(false);
	
		if(IS_POST){
			if (!empty($_FILES['file_excel']['name'] )){
				$tmp_file = $_FILES ['file_excel']['tmp_name'];
				
				$file_types = explode ( ".", $_FILES ['file_excel'] ['name'] );
				$file_type = $file_types [count($file_types) - 1];
				/*判别是不是.xls文件，判别是不是excel文件*/
				if (strtolower ( $file_type ) != "xls")
				{
					$this->error ( '不是Excel文件，重新上传' );
				}
				/*设置上传路径*/
				$savePath = './Upload/';
				/*以时间来命名上传的文件*/
				$str = date ( 'Ymdhis' );
				$file_name = $str . "." . $file_type;
				/*是否上传成功*/
				if (!copy( $tmp_file, $savePath . $file_name ))
				{
				$this->error ( '上传失败' );
				}
				/*
				*对上传的Excel数据进行处理生成编程数据,这个函数会在下面第三步的ExcelToArray类中
				注意：这里调用执行了第三步类里面的read函数，把Excel转化为数组并返回给$res,再进行数据库写入
				*/
				$res = excelRead( $savePath . $file_name );
				/*
				重要代码 解决Thinkphp M、D方法不能调用的问题
				如果在thinkphp中遇到M 、D方法失效时就加入下面一句代码
				*/
				//spl_autoload_register ( array ('Think', 'autoload' ) );
				/*对生成的数组进行数据库的写入*/
				//dump($res);return;
				$countrecord = count($res)-2;
				$errorrecord = 0;
				$apprecord = 0;
				$updaterecord = 0;
				$peopleModel = M('People');
				$computerModel = M('computer');
				foreach ( $res as $k => $v ){
					if ($k > 2){ //第1行为注释，第2行为表头
						$peopleId = $peopleModel->where("idnum='".$v [1]."'")->getField('id');
						
						
						$computerId = $computerModel->where("id='".$v [2]."'")->getField('id');
						if($peopleId){
							$data ['people_id'] = $peopleId;
							//echo $peopleId;return;
						}else{
							$data ['people_id'] = 0;
						//	$this->toLog('第'.$k.'行有错误，没有找到对应的人员基本信息，导入数据库失败');
							$errorrecord++;
							echo $v[0]."　　　　<span style=\"color:#ff0000;\">没有找到对应的人员基本信息，导入数据库失败</span>";
							echo "<br/>";
							echo "<br/>";
							//return;
							continue;
						}
						
						if($computerId){
							$data ['computer_id'] = $computerId;
							//echo $peopleId;return;
						}else{
							$data ['computer_id'] = 0;
						//	$this->toLog('第'.$k.'行有错误，没有找到对应的人员基本信息，导入数据库失败');
							$errorrecord++;
							echo $v[0]."　　　　<span style=\"color:#ff0000;\">没有找到对应计算机场次，导入数据库失败</span>";
							echo "<br/>";
							echo "<br/>";
							//return;
							continue;
						}
					
							$result = M('computer_people');
							$condition['people_id'] = $peopleId;
							$condition['computer_id'] = $v[2];
							$pd = $result->where($condition)->getField('id');
//							 var_dump($computerId);
//							 var_dump($peopleId);
//							die;
							//var_dump($pd);die;
						
							if($pd){
								$where['id'] = $pd;
								$result->where($where)->save($data);
								$updaterecord++;
							echo $v[0]."　　　　<span style=\"color:green;\">重复记录，更新信息成功</span>";
							echo "<br/>";
							echo "<br/>";
							}else
								{
							    $resu =	$result->add($data);
							
					  	      $apprecord++;
					           echo $v[0]."　　　　<span style=\"color:blue;\">记录添加成功</span>";
								//echo $v[0]."　　记录添加成功";
								echo "<br/>";
						    	echo "<br/>";
						         }
						}
						}
						//echo $peopleModel->_sql().'<br>';
					//}

				//} 
          echo '共有记录：'.$countrecord.',   更新记录：'.$updaterecord.',  新加记录：'.$apprecord.",  失败记录：".$errorrecord;
			//$this->success("导入完成");
			}
		}else{
			$this->display();
		}
	}
    

// 成绩导入
	public function tomark(){
		    header("Content-type: text/html; charset=utf-8");
			layout(false);
	
		if(IS_POST){
			if (!empty($_FILES['file_excel']['name'] )){
				$tmp_file = $_FILES ['file_excel']['tmp_name'];
				
				$file_types = explode ( ".", $_FILES ['file_excel'] ['name'] );
				$file_type = $file_types [count($file_types) - 1];
				/*判别是不是.xls文件，判别是不是excel文件*/
				if (strtolower ( $file_type ) != "xls")
				{
					$this->error ( '不是Excel文件，重新上传' );
				}
				/*设置上传路径*/
				$savePath = './Upload/';
				/*以时间来命名上传的文件*/
				$str = date ( 'Ymdhis' );
				$file_name = $str . "." . $file_type;
				/*是否上传成功*/
				if (!copy( $tmp_file, $savePath . $file_name ))
				{
				$this->error ( '上传失败' );
				}
				/*
				*对上传的Excel数据进行处理生成编程数据,这个函数会在下面第三步的ExcelToArray类中
				注意：这里调用执行了第三步类里面的read函数，把Excel转化为数组并返回给$res,再进行数据库写入
				*/
				$res = excelRead( $savePath . $file_name );
				/*
				重要代码 解决Thinkphp M、D方法不能调用的问题
				如果在thinkphp中遇到M 、D方法失效时就加入下面一句代码
				*/
				//spl_autoload_register ( array ('Think', 'autoload' ) );
				/*对生成的数组进行数据库的写入*/
				//dump($res);return;
				$countrecord = count($res)-2;
				$errorrecord = 0;
				$apprecord = 0;
				$updaterecord = 0;
				$peopleModel = M('People');
				
				foreach ( $res as $k => $v ){
					if ($k > 2){ //第1行为注释，第2行为表头
						$peopleId = $peopleModel->where("idnum='".$v [1]."'")->getField('id');
						$result = M('computer_people');
							$condition['people_id'] = $peopleId;
							$condition['computer_id'] = $v[2];
							$pd = $result->where($condition)->getField('id');
//							 var_dump($computerId);
//							 var_dump($peopleId);
//							die;
							//var_dump($pd);die;
						
							if($pd){
								$where['id'] = $pd;
								$data['sate'] = $v[3];
								$result->where($where)->save($data);
								$condit['id'] = $peopleId;
								if($v['3'] == '合格'){
									
									$dataexam['missexam'] = '';
								}else{
									
									$dataexam['missexam'] = $v[3];
								}
								$peopleModel->where($condit)->save($dataexam);
								$updaterecord++;
							echo $v[0]."　　　　<span style=\"color:green;\">计算机成绩导入信息成功</span>";
							echo "<br/>";
							echo "<br/>";
							
							
						}else{
							
							$errorrecord++;
							echo $v[0]."　　　　<span style=\"color:#ff0000;\">没有找到对应的人员，成绩导入失败</span>";
							echo "<br/>";
							echo "<br/>";
							//return;
							continue;
							
						}
						
					}
				}
				//} 
          echo '本次成绩导入总记录数：'.$countrecord.',   成绩导入记录数：'.$updaterecord.',  成绩导入失败记录数：'.$errorrecord;
			//$this->success("导入完成");
			}
		}else{
			$this->display();
		}
	}
    













	private function toLog($content){
		$logFile = 'ExcelErrorLog.txt';
		file_put_contents($logFile, $content."\r\n", FILE_APPEND);
	}
	
	
	//更新人员批次编号
	private function updataPeopleBatchAndBnumByIdnum($idnum, $batch, $b_num){
		$peopleModel = M("People");
		$data['batch'] = $batch;
		$data['b_num'] = $b_num;
		$peopleModel->where(array('idnum'=>$idnum))->data($data)->save();
	}
	
	//更新人员批次年度编号
	private function updataPeopleFileYearNumByIdnum($idnum, $fileYearNum, $check_type){
		$peopleModel = M("People");
		$data['file_year_num'] = $fileYearNum;
		$data['check_type'] = $check_type;
		$peopleModel->where(array('idnum'=>$idnum))->data($data)->save();
	}	
}

