<?php $results = get_results_for_event(get_the_ID()); if (count($results) > 0) { ?>
				<div class="col-xs-12 col-sm-8 col-md-9">
					<div class="table-responsive">
						<table class="table table-condensed" style="width:100%">
							<tr>
								<th class="text-center hidden-xs hidden-sm">Date</th>
								<th class="text-center">Team</th>
								<th class="text-center hidden-xs">Div<span class="hidden-sm"> (Seed)</span></th>
								<th class="text-center">Place</th>
								<th class="text-center">F/T</th>
								<th class="text-center hidden-xs" style="width:170px;">&nbsp;</th>
							</tr>
							<?php foreach ($results as $team){
								$raceDate = DateTime::createFromFormat('Ymd', $team->race_date);
								$teamCell = (isset($team->slug)) ? '<a href="/teams/'.$team->slug.'">'.$team->team.'</a>' : $team->team;
								$division = '';
								if ($team->division > 0){
									$division = 'Div '.$team->division;
									if ($team->seed > 0) { $division .= '<span class="hidden-xs"> ('.$team->seed.getOrdinal($team->seed).')</span>'; }
								}
								$place = $team->place;
								if ($place == 1){ $place = '<span class="text-primary"><strong>1st</strong></span>'; }
								elseif ($place > 0){ $place .= getOrdinal($team->place); }

								$time = '--:--';
								if ($team->fastest_time > 0){ 
									if ($team->team_type == 'sync' || $team->team_type == 'tag'){ $time = '<em>'.strtoupper($team->team_type).'</em>'; }
									else $time = $team->fastest_time;
								}

								$newFT = ($team->new_fastest) ? '<span class="label label-primary">New<span class="hidden-sm hidden-xs"> Fastest Time</span></span>' : '';

								echo '
									<tr>
										<td class="text-center hidden-xs hidden-sm">'.$raceDate->format('jS M Y').'</td>
										<td class="text-center">'.$teamCell.'</td>
										<td class="text-center hidden-xs">'.$division.'</td>
										<td class="text-center">'.$place.'</td>
										<td class="text-center">'.$time.'</td>
										<td class="text-center hidden-xs">'.$newFT.'</td>							
									</td>';
							} ?>
						</table>
						</div>
				</div>
				<?php } ?>