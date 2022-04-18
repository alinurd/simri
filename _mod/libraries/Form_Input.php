<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Form_Inputx {
	private $ci;
	private $params=[];
	private $result=[];
	private $_error='';
	public $image_no=0;

	function __construct()
	{
        $this->ci =& get_instance();
        $this->config->load('configuration', TRUE);
        $this->params =$this->config->item('default_list', 'configuration');
    }

    function set_Param($keys,$value=''){
        if (is_array($keys)){
            foreach($keys as $key=>$row){
                $this->params[$key]=$row;
            }
        }else{
            $this->params[$keys]=$value;
        }
        return  $this;
    }

    function draw(){
        $required="";
		$error="";
		$placeholder = '';
		$disabled='';
		$align='text-left';
		$typeahead='';
		$readOnly='';
		$btn_add='';
		$group="";
		$content='';
		$width="20%";
		$width_multi="510px";
		$btn_label_accordion='';
		$autofocus='';
		$nopass=0;
		$required='';
		$feedBack='';
		$feedBackContent='';

		$type = $row['input'];
		switch ($type){
			case 'int':
			case 'integer':
			case 'intdot':
			case 'integerdot':
			case 'float':
				$align='text-right';
				break;
			default:
				$align='text-left';
				break;
		}

		if (array_key_exists('align',$row)){
			$align = 'text-'.$row['align'];
		}
		
		if (array_key_exists('alias',$row)){
			$label= $row['alias'];
		}else{
			$label=$row['field'];
		}
		
		
		if ($row['bidFeedBack']){
			$feedBack = 'form-group form-group-feedback form-group-feedback-'.$row['bidFeedBackAlign'];
			$feedBackContent = $row['bidFeedBackContent'];
		}
		if (!empty($row['placeholder']))
			$placeholder = 'placeholder="'.$row['placeholder'].'"';

		if ($row['required'])
			$required = 'required="required"';

		if (array_key_exists('disabled',$row))
			$disabled = 'disabled';
		
		if (_MODE_=='view')
			$disabled = ' disabled ';
		
		$content = "";
		
		switch ($type){
			case 'plaintext':
				$content = '<div class="form-control-plaintext">'.$isi.'</div>';
				break;
			case 'string':
			case 'text':
				$size = $row['size'].'%';
				if($row['size']==100 || !empty($feedBack)){
					$size = '100%';
				}
				
				$content = form_input($label,$isi," size='".$row['size']."' maxlength='".$row['max']."'  class='form-control $error $align $typeahead'  $disabled $required $readOnly $placeholder id=$label $autofocus style='width:$size !important;' ");
				if (!empty($disabled)){
					$content .= form_hidden($label,$isi);
				}
				break;
			case 'tag':
				$content = form_input($label, $isi," size=$row[size] class='form-control tokenfield $error $align' $required $disabled $placeholder id=$label $autofocus ");
				if (!empty($disabled)){
					$content .= form_hidden($label,$isi);
				}
				break;
			case 'multitext':
				$jmlhuruf=intval($row['size'])-intval(strlen($isi));
				++$this->i_left;
				$left='id_sisa_'.$this->i_left;
				$size="100%";
				if (intval($row['size'])>0){
					$content= form_textarea($label, $isi," id='$label' maxlength='$row[size]' size=$row[size] $disabled $placeholder $readOnly class='form-control $error $align' rows='2' cols='5' style='overflow: hidden; width: $size !important; height: 104px;'  onblur='_maxLength(this , \"$left\")' onkeyup='_maxLength(this , \"$left\")' data-role='tagsinput' $autofocus ");
					$content .='<br/><span class="text-warning">'.lang('msg_chr_left').' </span><span style="display:inline-block;height:20px;"><small><input id="'.$left.'" type="hidden" align="right" class="form-control" style="text-align:right;width:60px;" disabled="" name="f1_11_char_left" value="'.$jmlhuruf.'" size="5">'.lang('btn_chr_left').'<span id="span_'.$left.'"  align="right" class="badge badge-primary " name="f1_11_char_left">'.$jmlhuruf.'</small></span></span>';
				}else{
					$content= form_textarea($label, $isi," id='$label' maxlength='10000' size=$row[size] $disabled class='form-control $error $align' rows='2' cols='5' style='overflow: hidden; width: $size !important; height: 104px;' ");
				}
					if (!empty($disabled))
						$content .= form_hidden($label,$isi);

				break;
			case 'html':
				$content = form_textarea($label, $isi," id='$label' class='summernote'");
				// $content ='<div class="summernote">';
				// $content .=$isi;
				// $content .='</div>';

				break;
			case 'pass':
				$id_pass='password'.++$nopass;
				$result_pass="result".$nopass;
				$content= form_password($label,''," size=$row[size] $disabled $required class='form-control $error $align' autocomplete='off' id='$id_pass' $autofocus ");
				if (!empty($disabled))
					$content .= form_hidden($label,'');
				break;
			case 'int':
			case 'integer':
				$size = $row['size'].'%';
				if($row['size']==100 || !empty($feedBack)){
					$size = '100%';
				}

				$content= form_input($label,$isi," class='form-control angka $error $align'  maxlength='".$row['max']."' $disabled $required size=$row[size]  id=$label $readOnly $autofocus style='width: $size' ");
				if (!empty($disabled))
					$content .= form_hidden($label,$isi);
				break;
			case 'float':
				$isi = number_format(floatval($isi));
				if (empty($isi))
					$isi=0;
				
				$size = $row['size'].'%';
				if($row['size']==100 || !empty($feedBack)){
					$size = '100%';
				}

				$content= form_input($label,$isi," class='form-control rupiah $error $align' $disabled $required size=$row[size] style='width: $size'  id=$label $readOnly $autofocus ");
				if (!empty($disabled))
					$content .= form_hidden($label,$isi);
				break;
			case 'intdot':
			case 'integerdot':
				$content= form_input($label,$isi," class='form-control numericdot $error $align' $required $disabled size=$row[size]  id=$label $readOnly $autofocus ");
				if (!empty($disabled))
					$content .= form_hidden($label,$isi);
				break;
			case 'updown':
				if (empty($isi))
					$isi='';
				$content= form_input(array('type'=>'number','name'=>$label),$isi," $disabled $required class='form-control numeric $error $align' $readOnly size=$row[size] style='width:$row[size]% !important;' id=$label $autofocus ");
				if (!empty($disabled))
					$content .= form_hidden($label,$isi);
				break;
			case 'color':
				$content= form_input(array('type'=>'color','name'=>$label),$isi," $disabled class='$error form-control' size=$row[size] style='height:30px;width:80px;background-color:$isi;' id=$label $autofocus ");
				if (!empty($disabled))
					$content .= form_hidden($label,$isi);
				break;
			case 'range':
				if (!$isi)
					$isi=1;
				$content= form_input(array('type'=>'range','name'=>$label), $isi," class='$error form-control' id='".$label."'  min='".$row['minrange']."' max='".$row['maxrange']."' step='".$row['steprange']."' oninput='ageOutputId.value = $label.value' ");
				$content .='<output name="ageOutputName" id="ageOutputId">'.$isi.'</output>';
				break;
			case 'boolean':
				$content=  form_dropdown($label, array(''=>'-','0'=>lang('cbo_no'),'1'=>lang('cbo_yes')), $isi,"id=$label $disabled $required style='height:30px;width:100px' class='form-control' $autofocus ");
				if (!empty($disabled))
					$content .= form_hidden($label,$isi);
				break;
			case 'bool:switch':
				$content="";
				$check=false;
				if ($isi){
					$check=true;
				}

				$content .= form_hidden($label,$isi);
				$content .='<div class="form-check form-check-switchery form-check-inline form-check-switchery-double">
				<label class="form-check-label">';
				$content .= lang('cbo_no');
				$content .= form_checkbox($label, $isi, $check, 'id="'.$label.'" class="pointer form-switchery-primary" ');
				$content .= lang('cbo_yes');
				$content .='</label></div>';
				break;
			case 'boolean:string':
				$content=  form_dropdown($label, array(''=>'-','N'=>lang('msg_cbo_no'),'Y'=>lang('msg_cbo_yes')), $isi,"id=$label $disabled style='height:30px;width:100px' class='form-control' $autofocus ");
				if (!empty($disabled))
					$content .= form_hidden($label,$isi);
				break;
			case 'combo':
				$multi='';
				// $size = $row['size'] . 'px'[]
				$size=$row['size']*5;
				$size .='px';
				if ($row['multiselect']){
					$size = '100%';
					$multi= ' multiple="multiple" ';
					$label = $label .'[]';
					if (!is_array($isi))
						$isi = explode(',',$isi);
				}elseif($row['size']==100){
					$size = '100%';
				}
				$content= form_dropdown($label, $row['values'], $isi,"id=$label $disabled class='$error form-control select' style='width:$size;'  $autofocus $multi ");
				if (!empty($disabled))
					$content .= form_hidden($label,$isi);
				break;
			case 'date':
				$size='auto';
				if($row['size']==100){
					$size = '100%';
				}elseif($row['size']==0){
					$size = 'auto';
				}

				$tgl=date('d-m-Y');
				if (!empty($isi)){
					if($isi=='01-01-1970')
						$isi=date('d-m-Y');
					else
						$isi=date('d-m-Y',strtotime($isi));
				}
				$content ='<div class="input-group">
							<span class="input-group-prepend">
								<span class="input-group-text"><i class="icon-calendar3"></i></span>
							</span>';
				$content .= form_input($label,$isi," id=$label size=$row[size] class='form-control $error pickadate-editable' $disabled $required style='width:$size;'  $readOnly $autofocus ");
				$content .='</div>';
				if (!empty($disabled))
					$content .= form_hidden($label,$isi);
				break;

			case 'time':
				$size='auto';
				if($row['size']==100){
					$size = '100%';
				}elseif($row['size']==0){
					$size = 'auto';
				}
				
				$tgl=date('d-m-Y H:i');
				if (!empty($isi))
					$tgl=date('d-m-Y H:i',strtotime($isi));

				$content ='<div class="input-group"><span class="input-group-prepend">
							<span class="input-group-text"><i class="icon-alarm"></i></span>
						</span>';

				$content .= form_input($label,$isi," id=$label size=$row[size] class='form-control $error pickatime-editable' $disabled $required style='width:$size px;'  $readOnly $autofocus ");
				$content .='</div>';
				if (!empty($disabled))
					$content .= form_hidden($label,$isi);
				break;
			case 'upload':
				$content ='';
				$o = '<img id="img_'.$label.'" style="width:40%; margin-top:10px;"  src="" alt="image"/>';
				$oo="";
				if (!empty($isi)){
					$kel='image';
					if (array_key_exists('path', $row)){
						$pt=explode('/', $row['path']);
						$path=$pt[0].'_path_relative';
						$info=pathinfo($path($isi));
						$info_ci=get_file_info($path($isi));
						$url=$pt[0].'_url';
						$url=$url($isi);
						$kel='file';//$row['path'];
					}else{
						$info=pathinfo(img_path_relative($isi));
						$info_ci=get_file_info(img_path_relative($isi));
						$url=img_url($isi);
					}
					
					if ($info['extension']=="jpg" || $info['extension']=="png" || $info['extension']=="jpeg" || $info['extension']=="gif" || $info['extension']=="bmp"){
						$o = '<img id="img_'.$label.'"  src="'.$url.'" alt="image"/>';
					}
					$nmFunc = $kel .'_path_relative';
					$size=0;
					if ($info_ci){
						if ($info_ci['size']>2000)
							$size = number_format($info_ci['size']/1024). ' kb';
						else
							$size = $info_ci['size'].' byte';

					}
					$oo='<br/><span class="well"><span data-url="'.base_url('ajax/download_preview/').'" data-target="'.$kel.'" data-file="'.$isi.'" class="preview_file pointer text-primary">'.$isi.'</span></span><br/><span style="padding-left:19px;">Size : '.$size.'</span><br/>&nbsp;<br/>';
				}
				$content =$o.$oo;
				$content .=form_upload($label,'','onchange="showMyImage(this,\'img_'.$label.'\')"');
				break;
			case 'radio':
				$br='form-check-inline';
				if (array_key_exists('vertical',$row))
					if ($row['vertical'])
						$br='';
				$content="";
				foreach($row['combo'] as $key=>$cbo){
					$check=false;
					if ($isi==$key){
						$check=true;
					}
					$content .='<div class="form-check  '.$br.'">
					<label class="form-check-label">';
					$content .= form_radio($label, $key, $check, 'id="'.$label.'_'.$key.'"  class="form-check-primary" ');
					$content .= form_label($cbo.' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $label.'_'.$key, ['class'=>'pointer']);
					$content .='</label></div>';
				}
				break;
			case 'check':
				$br='form-check-inline';
				if (array_key_exists('vertical',$row))
					if ($row['vertical'])
						$br='';
		
				$content="";
				if (empty($isi))
				$isi='';

				$isi=explode(",",$isi);
					
				foreach($row['combo'] as $key=>$cbo){
					$check=false;
					if (in_array($key, $isi)){
						$check=true;
					}
					$content .='<div class="form-check '.$br.'">
					<label class="form-check-label">';
					$content .= form_checkbox($label.'[]', $key, $check, 'id="'.$label.'_'.$key.'" class="form-check-primary" ');
					$content .= form_label($cbo.' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $label.'_'.$key, ['class'=>'pointer']);
					$content .='</label></div>';
				}
				break;
			case 'switch':
				$content="";
				$isi=true;
				$check=false;
				if ($isi){
					$check=true;
				}

				$content .='<div class="form-check form-check-switchery form-check-inline form-check-right">
				<label class="form-check-label">';
				$content .= form_checkbox($label, $isi, $check, 'id="'.$label.'" class="pointer form-switchery-primary" ');
				$content .= 'Test saja';
				$content .='</label></div>';
				
				break;

		}

		$contents['content'] = $content;
		$contents['feedBack'] = $feedBack;
		$contents['feedBackContent'] = $feedBackContent;
		return $contents;
    }
    
    function error(){
        return $this->_error;
    }
    function result($info=''){
        if (empty($info)){
            dump($this->result);
        }else{
            if (array_key_exists($info, $this->result))
                return $this->result[$info];
            else
                return 'unknow key';
        }
    }
}

/* End of file Authentication.php */
/* Location: ./application/libraries/Authentication.php */