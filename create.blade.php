@extends('../template')

@section('content')
	<!-- Page header -->
	<!--<div class="page-header">
		<div class="page-header-content">
			<div class="page-title">
				<h4>
					<i class="icon-arrow-left52 position-left"></i>
					<span class="text-semibold">Home</span> - Product / Create
				</h4>
			</div>
		</div>
	</div>-->
	<!-- /page header -->


	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">
				<div class="row">
					<div class="col-md-12">
						<!-- Vertical form -->
						<div class="panel panel-flat">
							<div class="panel-heading">
								<div class="heading-elements">
									<ul class="icons-list">
										
									</ul>
								</div>
							</div>
							
							<form method="post" action="{{url('product_create')}}" enctype="multipart/form-data">
							<input type="hidden" name="page" value="2" readonly>
							{{ csrf_field() }}
							<div class="panel-body">
								<div class="row">
									<div class="col-md-6 col-md-6 col-md-offset-3">
										<fieldset>
											<legend class="text-semibold">รายละเอียดสินค้า</legend>
											<div class="form-group">
												<label>รูปสินค้า :</label>
												<input type="file" class="file-input" name="uploadcover">
												<span class="help-block">ขนาดรูป : 305 x 425px</span>
											</div>
											
											<div class="form-group">
												<label>Gallery picture :</label>
												<input type="file" class="file-input" name="images_upload[]" multiple >
											</div>

											<div class="form-group" style="display:none;">
												<label>ชื่อโรงงาน :</label>
												<div class="input-control">
													<select class="select" name="factory" id="factory">
														<option value="">Choose</option>
														@php
														if($factory){
															foreach($factory as $fac){
																@endphp
																<option value="{{$fac->factory_name}}">{{$fac->factory_name}}</option>
																@php
															}
														}
														@endphp
													</select>
												</div>
											</div>
											
											<div class="form-group">
												<label>ชื่อสินค้า :</label>
												<div class="input-control">
													<input type="text" class="form-control" name="productname" id="productname" placeholder="Product" required>
												</div>
											</div>
											
											<div class="form-group">
												<label>รายละเอียดสินค้า :</label>
												<div class="input-control">
													<textarea rows="3" cols="5" class="form-control" name="productdetail"></textarea>
												</div>
											</div>
											
											<div class="form-group">
												<label>จำนวนสินค้าขั้นต่ำ :</label>
												<div class="input-control">
													<input type="number" class="form-control" name="productminstock" id="productminstock" placeholder="Product" required value="1"> 
												</div>
											</div>
											<div class="form-group" style="display:none;">
												<label>สินค้าชุด :</label>
												<div class="input-control">
													<input type="checkbox" class="styled" name="productset" id="productset" checked>
												</div>
											</div>
											
											<div class="row">
												<input type="hidden" id="countrow" value="1">
												<div class="form-group" id="productrowset">
													<div class="col-md-6">
														<label>สินค้าชุด</label>
														<div class="input-control">
															<select class="select" name="productsetarr[]">
																<option value="">Choose</option>
																@php
																	if($product){
																		foreach($product as $pro){
																			$color = '';
																			$size = '';
																			if($pro->cl_name != 'ไม่มี'){
																				$color = 'สี'.$pro->cl_name;
																			}
																			if($pro->sz_name != 'ไม่มี'){
																				$size = $pro->sz_name;
																			}
																			@endphp
																				<option value="{{$pro->id}}">{{$pro->product_name.' '.$color.' '.$size}}</option>
																			@php
																		}
																	}
																@endphp
															</select>
														</div>
													</div>
													
													<div class="col-md-3">
														<label>&nbsp;</label>
														<div class="input-control">
															<input type="text" class="form-control number"  name="productsetqty[]" placeholder="จำนวน">
														</div>
													</div>
													
													<div class="col-md-3">
														<label>&nbsp;</label>
														<div class="input-control">
															<button type="button" class="btn border-success text-success btn-flat btn-icon prosetadd"><i class="icon-plus-circle2"></i></button>
														</div>
													</div>
												</div>
											</div>
											<br>
											
											<div class="row" style="display:none;">
												<div class="form-group">
													<div class="col-md-3">
														<label>กว้าง :</label>
														<input type="text" class="form-control" name="productwidth" id="productwidth" style="width:250px;">
													</div>
													<div class="col-md-7  col-md-offset-2">
														<label>หน่วย :</label>
														<select class="form-control" name="unitwidth" id="unitwidth" style="width:250px;">
															<option value="">Choose</option>
															@if(!empty($unitoflength))
															@foreach($unitoflength as $item)
															<option value="{{$item->unitoflength_id}}">{{$item->unitoflength_name}}</option>
															@endforeach
															@endif
														</select>
													</div>
												</div>
												<br>
											</div>
											
											<div class="row" style="display:none;">
												<div class="form-group">
													<div class="col-md-3">
														<label>สูง :</label>
														<input type="text" class="form-control" name="productheigth" id="productheigth" style="width:250px;">
													</div>
													<div class="col-md-7  col-md-offset-2">
														<label>หน่วย :</label>
														<select class="form-control" name="unitheigth" id="unitheigth" style="width:250px;">
															<option value="">Choose</option>
															@if(!empty($unitoflength))
															@foreach($unitoflength as $item)
															<option value="{{$item->unitoflength_id}}">{{$item->unitoflength_name}}</option>
															@endforeach
															@endif
														</select>
													</div>
												</div>
												<br>
											</div>
											
											<div class="row" style="display:none;">
												<div class="form-group">
													<div class="col-md-3">
														<label>ยาว :</label>
														<input type="text" class="form-control" name="productlong" id="productlong" style="width:250px;">
													</div>
													<div class="col-md-7  col-md-offset-2">
														<label>หน่วย :</label>
														<select class="form-control" name="unitlong" id="unitlong" style="width:250px;">
															<option value="">Choose</option>
															@if(!empty($unitoflength))
															@foreach($unitoflength as $item)
															<option value="{{$item->unitoflength_id}}">{{$item->unitoflength_name}}</option>
															@endforeach
															@endif
														</select>
													</div>
												</div><br>
											</div>
											
											
											<div class="row" id="capital">
												<div class="form-group">
													<div class="col-md-3">
														<label>ราคาทุน :</label>
														<div class="input-control">
															<input type="text" class="form-control number inputc" name="productbuy" id="productbuy" value="0" required style="width:250px;"><br>
														</div>
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="form-group">
													<div class="col-md-3">
														<label>ราคาขาย :</label>
														<div class="input-control">
															<input type="text" class="form-control number inputc" name="productsale1" id="productsale1" value="0" required style="width:250px;">
														</div>
													</div>
												</div>
											</div>
											<br>
											<div class="row">
												<div class="form-group">
													<div class="col-md-3">
														<label>ค่า commission :</label>
														<div class="input-control">
															<input type="text" class="form-control number inputc" name="productcommission" id="productcommission" value="0" required style="width:250px;">
														</div>
													</div>
												</div>
											</div>
											<br>
											<div class="row datepick">
												<div class="form-group">
													<div class="col-md-5">
														<label>วันที่เริ่มโปรโมชั่น :</label>
														<div class="input-control">
															<input type="text" name="productprosdate" id="productprosdate" class="form-control datepicker-th" autocomplete="off" style="width:250px;">
														</div>
													</div>
													
													<div class="col-md-5  col-md-offset-2">
														<label>วันที่สิ้นสุดโปรโมชั่น :</label>
														<div class="input-control">
															<input type="text" name="productproedate" id="productproedate" class="form-control datepicker-th"  autocomplete="off" style="width:250px;">
														</div>
													</div>
												</div>
											</div>
											<br>
											<div class="form-group">
												<label>ราคาโปรโมชั่น (บาท) :</label>
												<div class="input-control">
													<input type="radio" class="styled" name="prostatus" id="prostatus" value="0" checked>
												</div>
												
												<label>ราคาโปรโมชั่น (เปอเซ็นต์) :</label>
												<div class="input-control">
													<input type="radio" class="styled" name="prostatus" id="prostatus" value="1">
												</div>
											</div>
											
											<div class="row">
												<div class="form-group">
													<div class="col-md-3">
														<label>ราคาโปรโมชั่น :</label>
														<div class="input-control">
															<input type="text" class="form-control number inputc" name="proprice" id="proprice" value="0" style="width:250px;">
														</div>
													</div>
												</div>
											</div>
											
											<div class="form-group" style="display:none;">
												<br>
												<label>สี :</label>
												<div class="input-control">
													<input type="text" class="form-control" name="productcolor" id="productcolor" required value="ไม่มี">
													<span class="help-block">กรณีมีมากว่า 1 สีให้ใส่ comma ( , ) คั่่น ตัวอย่าง สีดำ,สีขาว,สีน้ำเงิน  กรณีไม่มีสีให้ระบุว่า : ไม่มี</span>
												</div>
											</div>
											<br>
											
											<div class="row">
												<div class="form-group">
													<div class="col-md-3">
														<label>หน่วยสินค้า :</label>
														<select class="form-control" name="productunit" id="productunit" required style="width:250px;">
															<option value="">เลือก</option>
															@php
																if($unit){
																	foreach($unit as $rs){
																		@endphp
																			<option value="{{$rs->unit_id}}">{{$rs->unit_name}}</option>
																		@php
																	}
																}
															@endphp
														</select>
													</div>
													
													{{-- <div class="col-md-7  col-md-offset-2">
														<label>ไซต์ </label>
														<input type="text" class="form-control" name="productsize" id="productsize" value="">
													</div> --}}
												</div>
											</div>
											
											<br>
										
											<div class="row">
												<div class="form-group">
													<div class="col-md-3">
														<label>หมวดหมู่สินค้า :</label>
														<select class="form-control" name="category" id="category" required style="width:250px;">
															<option value="" selected>เลือก</option>
															@php
																if($category){
																	foreach($category as $rs){
																		@endphp
																			<option value="{{$rs->category_id}}">{{$rs->category_name}}</option>
																		@php
																	}
																}
															@endphp
														</select>
													</div>
													<div class="col-md-7  col-md-offset-2">
														<label>หมวดหมู่ย่อย :</label>
														<select class="form-control" name="subcategory" id="subcategory" style="width:250px;"></select>
													</div>
												</div>
											</div>
                                            <br>
                                            <div class="row">
												<div class="form-group">
													<div class="col-md-3">
														<label>สินค้าขายประจำ :</label>
														<label class="checkbox-inline checkbox-switchery checkbox-right switchery-md" style="margin-top: -13px;">
															<input type="checkbox" class="switch" value="off">
                                                            <input type="hidden" name="recommended" id="recommended" value="off">
														</label>
													</div>
												</div>
											</div>
											
											<br>
											<div class="text-right">
												<a href="{{url('promotion')}}"><button type="button" class="btn btn-danger"><i class="icon-rotate-ccw3"></i>  ยกเลิก</button></a>
												<button type="submit" class="btn btn-primary"><i class="icon-floppy-disk"></i>  บันทึก</button>
											</div>
										</fieldset>
									</div>
								</div>
								</form>
							</div>
						</div>
						<!-- /vertical form -->
					</div>
				</div>
			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->

<script>
	$(document).ready(function(){
		$(".inputc").focus(function(){
			$(this).val('');
		});
  
		//$('#category').val(1);
		//$('#productunit').val(4);
	});
	
	$('#productsale1').keyup(function(){
		$(this).val(function(index, value) {
			return value
			.replace(/(?!-)[^0-9.]/g, "")
			.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		});
	});
	
	$('#proprice').keyup(function(){
		$(this).val(function(index, value) {
			return value
			.replace(/(?!-)[^0-9.]/g, "")
			.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		});
	});
	
	var options = '';
	$(document).ready(function(){
        var switches = Array.prototype.slice.call(document.querySelectorAll('.switch'));
		switches.forEach(function(html) {
			var switchery = new Switchery(html, {color: '#4CAF50'});
		});
	});
    
    $(document).on('click','.switchery',function(){
		var check = $(this).parent().find('input').val();
		if(check == 'on'){
			$(this).parent().find('input').val("off");
		}else{
			$(this).parent().find('input').val("on");
		}
	});
	
	$('#productsize').keyup(function(){
		var v = $(this).val();
		console.log(1111);
	});
		
	
	function formatNumber (x) {
		return x.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
	}
	
	/* $('#productset').click(function(){
		if($(this).is(':checked') == true){
			$('#productrowset').show();
		}else{
			$('#productrowset').hide();
		}
	}); */
	
	$('#category').change(function(){
		$.ajax({
		'dataType': 'json',
		'type': 'post',
		'url': "{{url('product_category')}}",
		'data': {
			'id': $('#category').val(),
			'_token': "{{ csrf_token() }}"
		},
			'success': function (data) {
				$('#subcategory').html('');
				$.each(data,function(key,item){
					$('#subcategory').append('<option value="'+item.sub_id+'">'+item.sub_name+'</option>');
				});
			}
		});
	});
	
	$('.prosetadd').click(function(){
		var countrow 	= $('#countrow').val()||0;
		var sumrow 		= parseInt(countrow)+(1);
		$('#countrow').val(sumrow);
		
		$.ajax({
		'dataType': 'json',
		'type': 'post',
		'url': "{{url('productjson')}}",
		'data': {
			'_token': "{{ csrf_token() }}"
		},
			'success': function(data){
				var html = '';
				$.each(data,function(k,v){
					var color 	= '';
					var size 	= '';
					if(v.cl_name != 'ไม่มี'){
						color = 'สี'+v.cl_name;
					}
					
					if(v.sz_name != 'ไม่มี'){
						size = 'สี'+v.sz_name;
					}
					html += '<option value="'+v.id+'">'+v.product_name+' '+color+'  '+size+'</option>';
				});
				
				$('#productrowset').append('<div id="rowset'+sumrow+'">'
					+'<div class="col-md-6">'
						+'<label>&nbsp;</label>'
						+'<div class="input-control">'
							+'<select class="select-search" name="productsetarr[]">'
								+'<option value="">Choose</option>'
								+html
							+'</select>'
						+'</div>'
					+'</div>'
					
					+'<div class="col-md-3">'
						+'<label>&nbsp;</label>'
						+'<div class="input-control">'
							+'<input type="text" class="form-control number"  name="productsetqty[]" placeholder="จำนวน">'
						+'</div>'
					+'</div>'
													
					+'<div class="col-md-3">'
						+'<label>&nbsp;</label>'
						+'<div class="input-control">'
							+'<button type="button" class="btn border-success text-success btn-flat btn-icon" onclick="prosetadd('+sumrow+')"><i class="icon-plus-circle2"></i></button>&nbsp;'
							+'<button type="button" class="btn border-danger text-danger btn-flat btn-icon" onclick="prosetdel('+sumrow+')"><i class="icon-trash"></i></button>'
						+'</div>'
					+'</div>'
				+'</div>');
				$('.select-search').select2();
			}
		});
	});
	
	function prosetadd(id){
		var countrow 	= $('#countrow').val()||0;
		var sumrow 		= parseInt(countrow)+(1);
		$('#countrow').val(sumrow);
		
		$.ajax({
		'dataType': 'json',
		'type': 'post',
		'url': "{{url('productjson')}}",
		'data': {
			'_token': "{{ csrf_token() }}"
		},
			'success': function(data){
				var html = '';
				$.each(data,function(k,v){
					var color 	= '';
					var size 	= '';
					if(v.cl_name != 'ไม่มี'){
						color = 'สี'+v.cl_name;
					}
					
					if(v.sz_name != 'ไม่มี'){
						size = 'สี'+v.sz_name;
					}
					html += '<option value="'+v.id+'">'+v.product_name+' '+color+'  '+size+'</option>';
				});
				
				$('#productrowset').append('<div id="rowset'+sumrow+'">'
					+'<div class="col-md-6">'
						+'<label>&nbsp;</label>'
						+'<div class="input-control">'
							+'<select class="select-search" name="productsetarr[]">'
								+'<option value="">Choose</option>'
								+html
							+'</select>'
						+'</div>'
					+'</div>'
				
					+'<div class="col-md-3">'
						+'<label>&nbsp;</label>'
						+'<div class="input-control">'
							+'<input type="text" class="form-control number"  name="productsetqty[]" placeholder="จำนวน">'
						+'</div>'
					+'</div>'
					
					+'<div class="col-md-3">'
						+'<label>&nbsp;</label>'
						+'<div class="input-control">'
							+'<button type="button" class="btn border-success text-success btn-flat btn-icon" onclick="prosetadd('+sumrow+')"><i class="icon-plus-circle2"></i></button>&nbsp;'
							+'<button type="button" class="btn border-danger text-danger btn-flat btn-icon" onclick="prosetdel('+sumrow+')"><i class="icon-trash"></i></button>'
						+'</div>'
					+'</div>'
				+'</div>');
				$('.select-search').select2();
			}
		});
	}
	
	function prosetdel(id){
		var countrow 	= $('#countrow').val()||0;
		var sumrow 		= parseInt(countrow)-(1);
		$('#countrow').val(sumrow);
		$('#rowset'+id).remove();
	}
</script>
@stop