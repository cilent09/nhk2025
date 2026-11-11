@extends('../template')

@section('content')
	<!-- Page header -->
	<!--<div class="page-header">
		<div class="page-header-content">
			<div class="page-title">
				<h4>
					<i class="icon-arrow-left52 position-left"></i>
					<span class="text-semibold">Home</span> - Product / Update
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
							
							<form method="post" action="{{url('product_update')}}" enctype="multipart/form-data">
							{{ csrf_field() }}
							<input type="hidden" name="page" value="1" readonly>
							<input type="hidden" name="updateid" value="{{$product->product_id}}">
							<input type="hidden" name="updatesz" value="{{$product->sz_id}}">
							<input type="hidden" name="updatecl" value="{{$product->cl_id}}">
							<div class="panel-body">
								<div class="row">
									<div class="col-md-6 col-md-6 col-md-offset-3">
										<fieldset>
											<legend class="text-semibold">รายละเอียดสินค้า</legend>
											<div class="form-group">
												<label>รูปสินค้า :</label>
												<input type="file" class="file-input" name="uploadcover">
												<span class="help-block">ขนาดรูป : 305 x 425px</span>
												@if(!empty($product->product_thumbs))
													<img src="{{asset('assets/images/product')}}/{{$product->product_thumbs}}" width="300px" class="img-thumbnail">
												@endif
											</div>
											
											<div class="form-group">
												<label>Gallery picture :</label>
												<input type="file" class="file-input" name="images_upload[]" multiple>
												<div class="row">
													<br>
													@if($productimg)
														@foreach($productimg as $rs)
															<div class="col-md-3">
																<img src="{{asset('assets/images/product')}}/{{$rs->images_name}}" class="img-thumbnail" onclick="imgdel({{$rs->images_id}})" width="100px">
															</div>
														@endforeach
													@endif
												</div>
											</div>
											<div class="form-group">
												<label>ชื่อโรงงาน :</label>
												<div class="input-control">
													<select class="select" name="factory" id="factory">
														@php
														if($factory){
															foreach($factory as $fac){
																if($fac->factory_name == $product->product_factory){
																	@endphp
																	<option value="{{$fac->factory_name}}" selected>{{$fac->factory_name}}</option>
																	@php
																}else{
																	@endphp
																	<option value="{{$fac->factory_name}}">{{$fac->factory_name}}</option>
																	@php
																}
																
															}
														}
														@endphp
													</select>
												</div>
											</div>
											<div class="form-group">
												<label>ชื่อสินค้า :</label>
												<div class="input-control">
													<input type="text" class="form-control" name="productname" id="productname" value="{{$product->product_name}}" placeholder="Product" required>
												</div>
											</div>
											
											<div class="form-group">
												<label>รายละเอียดสินค้า :</label>
												<div class="input-control">
													<textarea rows="3" cols="5" class="form-control" name="productdetail">{{$product->product_detail}}</textarea>
												</div>
											</div>
											<div class="form-group">
												<label>จำนวนสินค้าขั้นต่ำ :</label>
												<div class="input-control">
													<input type="number" class="form-control" name="productminstock" id="productminstock" placeholder="Product" required value="{{$product->product_minstock}}"> 
												</div>
											</div>
											<div class="form-group">
												<label>สินค้าเซ็ต :</label>
												<div class="input-control">
													<input type="checkbox" class="styled" name="productset" id="productset" @php if($product->product_status == 1){ @endphp checked @php } @endphp >
												</div>
											</div>
											
											@php
												if($dataset){
													foreach($dataset as $key => $set){
														$color = '';
														$size = '';
														if($set->cl_name != 'ไม่มี'){
															$color = 'สี'.$set->cl_name;
														}
														if($set->sz_name != 'ไม่มี'){
															$size = $set->sz_name;
														}
														@endphp
															<div id="rowset{{$key}}">
																<div class="col-md-6">
																	<label>&nbsp;</label>
																	<div class="input-control">
																		<input type="text" class="form-control" name="productsetarrname[]" value="{{$set->product_name.' '.$color.' '.$size}}" readonly>
																		<input type="hidden" class="form-control" name="productsetarr[]" value="{{$set->sub_product}}">
																	</div>
																</div>
																
																<div class="col-md-3">
																	<label>&nbsp;</label>
																	<div class="input-control">
																		<input type="text" class="form-control number"  name="productsetqty[]" value="{{$set->sub_qty}}" placeholder="จำนวน">
																	</div>
																</div>
													
																<div class="col-md-3">
																	<label>&nbsp;</label>
																	<div class="input-control">
																		<button type="button" class="btn border-danger text-danger btn-flat btn-icon" onclick="prosetdelup({{$key}})"><i class="icon-trash"></i></button>
																	</div>
																</div>
															</div>
														@php
													}
												}
											@endphp
													
											<div class="row">
												<input type="hidden" id="countrow" value="1">
												<div class="form-group" id="productrowset" style="display:none;">
													<div class="col-md-6">
														<label>&nbsp;</label>
														<div class="input-control">
															<select class="select" name="productsetarr[]">
																<option value="">Choose</option>
																@php
																	if($productg){
																		foreach($productg as $pro){
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

											<div class="row">
												<div class="form-group">
													<div class="col-md-3">
														<label>กว้าง :</label>
														<input type="text" class="form-control" name="productwidth" id="productwidth" style="width:250px;" value="{{$product->product_width}}">
													</div>
													<div class="col-md-7  col-md-offset-2">
														<label>หน่วย :</label>
														<select class="form-control" name="unitwidth" id="unitwidth" style="width:250px;">
															<option value="">Choose</option>
															@if(!empty($unitoflength))
															@foreach($unitoflength as $item)
																@if($item->unitoflength_id == $product->product_unitwidth)
																<option value="{{$item->unitoflength_id}}" selected>{{$item->unitoflength_name}}</option>
																@else
																<option value="{{$item->unitoflength_id}}">{{$item->unitoflength_name}}</option>
																@endif
															@endforeach
															@endif
														</select>
													</div>
												</div>
											</div>
											<br>
											<div class="row">
												<div class="form-group">
													<div class="col-md-3">
														<label>สูง :</label>
														<input type="text" class="form-control" name="productheigth" id="productheigth" style="width:250px;" value="{{$product->product_heigth}}">
													</div>
													<div class="col-md-7  col-md-offset-2">
														<label>หน่วย :</label>
														<select class="form-control" name="unitheigth" id="unitheigth" style="width:250px;">
															<option value="">Choose</option>
															@if(!empty($unitoflength))
															@foreach($unitoflength as $item)
																@if($item->unitoflength_id == $product->product_unitheigth)
																<option value="{{$item->unitoflength_id}}" selected>{{$item->unitoflength_name}}</option>
																@else
																<option value="{{$item->unitoflength_id}}">{{$item->unitoflength_name}}</option>
																@endif
															@endforeach
															@endif
														</select>
													</div>
												</div>
											</div>
											<br>
											<div class="row">
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
																@if($item->unitoflength_id == $product->product_unitlong)
																<option value="{{$item->unitoflength_id}}" selected>{{$item->unitoflength_name}}</option>
																@else
																<option value="{{$item->unitoflength_id}}">{{$item->unitoflength_name}}</option>
																@endif
															@endforeach
															@endif
														</select>
													</div>
												</div>
											</div>
											<br>
											
											<div class="row" id="capital">
												<div class="form-group">
													<div class="col-md-3">
														<label>ราคาทุน :</label>
														<div class="input-control">
															<input type="text" class="form-control number inputc" name="productbuy" id="productbuy" required style="width:250px;" value="{{number_format($product->product_buy)}}">
														</div>
													</div>
												</div>
											</div>
											<br>
											<div class="row">
												<div class="form-group">
													<div class="col-md-3">
														<label>ราคาขาย :</label>
														<div class="input-control">
															<input type="text" class="form-control number inputc" name="productsale1" id="productsale1" value="{{number_format($product->product_sale)}}" required style="width:250px;">
														</div>
													</div>
													
												</div>
											</div>
											<br>
											<div class="row">
												<div class="form-group">
													<div class="col-md-5">
														<label>วันที่เริ่มโปรโมชั่น :</label>
														@php
															if(!empty($product->product_prosdate)){
																$dataspro = date('d/m/Y',strtotime($product->product_prosdate));
															}else{
																$dataspro = '';
															}
														@endphp
														<div class="input-control">
															<input type="text" name="productprosdate" id="productprosdate" class="form-control datepicker-th" value="{{$dataspro}}" autocomplete="off" style="width:250px;">
														</div>
													</div>
													
													<div class="col-md-5  col-md-offset-2">
														<label>วันที่สิ้นสุดโปรโมชั่น :</label>
														@php
															if(!empty($product->product_proedate)){
																$dataepro = date('d/m/Y',strtotime($product->product_proedate));
															}else{
																$dataepro = '';
															}
														@endphp
														<div class="input-control">
															<input type="text" name="productproedate" id="productproedate" class="form-control datepicker-th" value="{{$dataepro}}" autocomplete="off" style="width:250px;">
														</div>
													</div>
												</div>
											</div>
											<br>
											<div class="form-group">
												<label>ราคาโปรโมชั่น (บาท) :</label>
												<div class="input-control">
													<input type="radio" class="styled" name="prostatus" id="prostatus" value="0" @php if($product->product_prostatus == 0){ @endphp checked @php } @endphp >
												</div>
												
												<label>ราคาโปรโมชั่น (เปอเซ็นต์) :</label>
												<div class="input-control">
													<input type="radio" class="styled" name="prostatus" id="prostatus" value="1" @php if($product->product_prostatus == 1){ @endphp checked @php } @endphp >
												</div>
											</div>
											
											<div class="row">
												<div class="form-group">
													<div class="col-md-3">
														<label>ราคาโปรโมชั่น :</label>
														<div class="input-control">
															<input type="text" class="form-control number inputc" name="proprice" id="proprice" value="{{$product->product_promotion}}" style="width:250px;">
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
															<input type="text" class="form-control number inputc" name="productcommission" id="productcommission" value="{{number_format($product->product_commission)}}" required style="width:250px;">
														</div>
													</div>
												</div>
											</div>
											<br>
											<div class="form-group">
												<label>สี :</label>
												<input type="text" class="form-control" name="productcolor" id="productcolor" value="{{$product->cl_name}}" required>
												<span class="help-block">กรณีมีมากว่า 1 สีให้ใส่ comma ( , ) คั่่น ตัวอย่าง สีดำ,สีขาว,สีน้ำเงิน  กรณีไม่มีสีให้ระบุว่า : ไม่มี</span>
											</div>
											<div class="row">
												<div class="form-group">
													<div class="col-md-3">
														<label>หน่วยสินค้า :</label>
														<select class="form-control" name="productunit" id="productunit" required style="width:250px;">
															<option value="">เลือก</option>
															@php
																if($unit){
																	foreach($unit as $rs){
																		if($rs->unit_id == $product->product_unit){
																		@endphp
																			<option value="{{$rs->unit_id}}" selected>{{$rs->unit_name}}</option>
																		@php
																		}else{
																		@endphp	
																			<option value="{{$rs->unit_id}}">{{$rs->unit_name}}</option>
																		@php
																		}
																	}
																}
															@endphp
														</select>
													</div>
													
													<div class="col-md-7  col-md-offset-2">
														<label></label>
														<input type="hidden" class="form-control" name="productsize" id="productsize" required value="{{$product->sz_name}}">
													</div>
												</div>
											</div>
											
											<br>
											<div class="row">
												<div class="form-group">
													<div class="col-md-3">
														<label>หมวดหมู่สินค้า :</label>
														<select class="form-control" name="category" id="category" required style="width:250px;">
															<option value="">เลือก</option>
															@php
																if($category){
																	foreach($category as $rs){
																		if($rs->category_id == $product->product_category){
																		@endphp
																			<option value="{{$rs->category_id}}" selected>{{$rs->category_name}}</option>
																		@php
																		}else{
																		@endphp
																			<option value="{{$rs->category_id}}">{{$rs->category_name}}</option>
																		@php
																		}
																	}
																}
															@endphp
														</select>
													</div>
													<div class="col-md-7  col-md-offset-2">
														<label>หมวดหมู่ย่อย :</label>
														<select class="form-control" name="subcategory" id="subcategory" style="width:250px;">
															<option value="">Choose</option>
															@if(!empty($subcategory))
															@foreach($subcategory as $item)
																@if($item->sub_id == $product->product_subcategory)
																<option value="{{$item->sub_id}}" selected>{{$item->sub_name}}</option>
																@else
																<option value="{{$item->sub_id}}">{{$item->sub_name}}</option>
																@endif
															@endforeach
															@endif
														</select>
													</div>
												</div>
											</div>
											 <br>
                                            <div class="row">
												<div class="form-group">
													<div class="col-md-3">
														<label>สินค้าขายประจำ :</label>
                                                        @php
                                                            $recom = 'off';
                                                            if($product->product_recommended == 1){
                                                                $recom = 'on';
                                                            }
                                                        @endphp
														<label class="checkbox-inline checkbox-switchery checkbox-right switchery-md" style="margin-top: -13px;">
															<input type="checkbox" class="switch" value="off">
                                                            <input type="hidden" name="recommended" id="recommended" value="{{$recom}}">
														</label>
													</div>
												</div>
											</div>
                                            
											<br>
											<div class="text-right">
												<a href="{{url('product')}}"><button type="button" class="btn btn-danger"><i class="icon-rotate-ccw3"></i>  ยกเลิก</button></a>
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
		
		if($('#productset').is(':checked') == true){
			$('#productrowset').show();
		}else{
			$('#productrowset').hide();
		}
	});
	
	$('#productset').click(function(){
		if($(this).is(':checked') == true){
			$('#productrowset').show();
		}else{
			$('#productrowset').hide();
		}
	});
	
	$('#proprice').keyup(function(){
		$(this).val(function(index, value) {
			return value
			.replace(/(?!-)[^0-9.]/g, "")
			.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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
	
	function prosetdelup(id){
		$('#rowset'+id).remove();
	}
	
	$('#productsale1').keyup(function(){
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
        
        if({{$product->product_recommended}} == 1){
            $('.switchery').trigger('click');
        }
	});
    
     $(document).on('click','.switchery',function(){
		var check = $(this).parent().find('input').val();
		if(check == 'on'){
			$(this).parent().find('input').val("off");
		}else{
			$(this).parent().find('input').val("on");
		}
	});
	
	function formatNumber (x) {
		return x.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
	}
	
	function imgdel(id){
		bootbox.confirm({
			title: "ยืนยัน?",
			message: "คุณต้องการลบรูปรายการนี้ หรือไม่?",
			buttons:{
				cancel: {
					label: '<i class="fa fa-times"></i> ยกเลิก',
					className: 'btn-danger'
				},
				confirm:{
					label: '<i class="fa fa-check"></i> ยืนยัน',
					className: 'btn-success'
				}
			},
			callback: function (result){
				if(result == true){
					window.location.href="../../../productimg-delete/"+id+"";
				}
			}
		});
	}
</script>
@stop