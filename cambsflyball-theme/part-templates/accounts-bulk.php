					<div class="row" style="margin-bottom: 15px;">
	                	<div class="col-md-12 hidden-xs hidden-sm">
	                		<div class="panel-group" role="tablist">
	                			<div class="panel panel-default">
	                				<div class="panel-heading" role="tab" id="collapseInvoicingHeading">
	                					<h4 class="panel-title">
	                						<a href="#collapseInvoicing" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapseInvoicing">Bulk Invoicing</a>
	                					</h4>
	                				</div>
									<div id="collapseInvoicing" class="panel-collapse collapse" role="tabpanel" aria-labelledby="collapseInvoicingHeading">
										<div class="panel-body">
											<div class="row">
												<div class="col-xs-12">
													<form class="form-horizontal" role="form" autocomplete="off" method="post" enctype="multipart/form-data" action="/admin/accounts/">
														<div class="form-group">
															<label for="date_in" class="sr-only">Date</label>
															<div class="input-group col-md-2 date">
																<span class="input-group-addon"><i class="far fa-calendar-alt"></i></span>
																<input type="text" id="date_in" name="date_in" class="form-control date_in" placeholder="Date" />
															</div>														
															<label for="category" class="sr-only">Category</label>
															<div class="col-md-3">
																<select class="form-control" id="category" name="category" title="Category">
																	<option value="" hidden>Category</option>
																	<option value="Flyball">Training Fees</option>
																	<option value="Entry Fees">Entry Fees</option>
																	<option value="Camping">Camping Fees</option>
																	<option value="Special Event">Special Event</option>
																	<option value="Bonus Ball">Bonus Ball</option>
																	<option value="Membership">Membership</option>
																	<option value="Merchandise">Merchandise</option>
																</select>
															</div>
															<label for="event_desc" class="sr-only">Event</label>
															<div class="col-md-3">
																<input type="text" id="event_desc" name="event_desc" class="form-control" placeholder="Event/Description" />
															</div>
															
															<div class="col-md-3">
																<label class="btn btn-default btn-block" for="csv_file" id="upload-file-btn">
																	<input id="csv_file" name="csv_file" type="file" style="display:none" onchange="jQuery('#upload-file-label').html(this.files[0].name);jQuery('#upload-file-btn').addClass('btn-success');" />
																	<span id="upload-file-label">Choose File</span>
																</label>
																<!-- <span class='label label-info' id="upload-file-info"></span> -->
															</div>
															<div class="col-md-1">
																<button type="submit" class="btn btn-block btn-primary" id="add_invoices" name="add_invoices" value="yes">Go!</button>
															</div>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>
	                			</div>
	                		</div>			
						</div>	                
	                </div>
