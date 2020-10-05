	                <?php $users = get_users( [ 'role__in' => [ 'author', 'editor' ] ] ); ?>
					<div class="row" style="margin-bottom: 15px;">
	                	<div class="col-md-12 hidden-xs hidden-sm">
	                		<div class="panel-group" role="tablist">
	                			<div class="panel panel-default">
	                				<div class="panel-heading" role="tab" id="collapseIncomeHeading">
	                					<h4 class="panel-title">
	                						<a href="#collapseIncome" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapseIncome">Add Individual Accounts</a>
	                					</h4>
	                				</div>
									<div id="collapseIncome" class="panel-collapse collapse" role="tabpanel" aria-labelledby="collapseIncomeHeading">
										<div class="panel-body">
											<div class="row">
												<div class="col-xs-12">
													<form class="form-horizontal" role="form" autocomplete="off" method="post" action="/admin/accounts/">
														<div class="form-group">
															<label for="date_in" class="sr-only">Date</label>
															<div class="input-group col-md-2 date">
																<span class="input-group-addon"><i class="far fa-calendar-alt"></i></span>
																<input type="text" id="date_in" name="date_in" class="form-control date_in" placeholder="Date" />
															</div>													
															<label for="user_id" class="sr-only">Member</label>
															<div class="col-md-3">
																<select class="form-control  id="user_id" name="user_id" title="Member">
																	<option value="" hidden>Member</option>
																	<?php foreach ($users as $user) {
																		echo '<option value="'.$user->ID.'">'.$user->display_name.'</option>';
																	} ?>
																</select>
															</div>															
															<label for="method" class="sr-only">Method</label>
															<div class="col-md-2">
																<select class="form-control" id="method" name="method" title="Method">
																	<option value="" hidden>Method</option>
																	<option value="DEPOSIT">BACS</option>
																	<option value="CONTRA">Contra</option>
																	<option value="CASH">Cash</option>
																	<option value="CHQ">Cheque</option>
																	<option value="INVOICE">Invoice</option>
																	<!-- <option value="PAYPAL">PayPal</option> -->
																</select>
															</div>															
															<!-- <label for="category" class="sr-only">Category</label>
															<div class="col-md-2">
																<select class="form-control" id="category" name="category" title="Category">
																	<option value="" hidden>Category</option>
																	<option value="Flyball">Flyball</option>
																</select>
															</div> -->
															<label for="description" class="sr-only">Description</label>
															<div class="col-md-2">
																<input type="text" id="description" name="description" class="form-control" placeholder="Description" />
															</div>	
															<label for="amount" class="sr-only">Amount</label>
															<div class="input-group col-md-2">
																<span class="input-group-addon"><i class="fas fa-pound-sign"></i></span>
																<input type="text" id="amount" name="amount" class="form-control" placeholder="Amount" />
															</div>
															<div class="col-md-1">
																<button type="submit" class="btn btn-block btn-primary" id="add_income" name="add_income" value="yes">Go!</button>
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