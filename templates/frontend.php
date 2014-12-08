<!-- ReDi restaurant reservation plugin version <?php echo $this->version ?> -->
<link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
<script type="text/javascript">var date_format = '<?php echo $calendar_date_format ?>';
		<?php $time_format_s = explode(':', $time_format);if(isset($time_format_s[0]) && in_array($time_format_s[0], array('g','h'))):?>var time_format = 'h:mm tt';
		<?php else: ?>var time_format = 'HH:mm';
		<?php endif ?>var locale = '<?php echo $js_locale?>';
	var datepicker_locale = '<?php echo $datepicker_locale?>';
	var timeshiftmode = '<?php echo $timeshiftmode; ?>';
	var hidesteps = <?php echo $hidesteps ? 1 : 0; ?>;
	var apikeyid = '<?php echo $apiKeyId; ?>';</script>
<div id="redi_f_box"><br><br>
	<input type="hidden" id="placeID" name="placeID" value="<?php echo $places[0]->ID ?>"/>

	<div class="f_close_icon">
		<div class="f_close_icon_text">CANCEL</div>
	</div>

	<div class="f_step_box">
		<div class="f_arrow_next_step"></div>
		<div class="f_active_step1"><br>
			<span class="underline">STEP 1</span></div>

		<div class="f_non_active_step2"><br>
			<span class="underline"> STEP 2</span></div>

	</div>

	<div id="f_tab_box">
		<p>&nbsp;</p>


		<p>

		<div class="f_subbox_title">
			<table width="335px" border="0" align="center" cellspacing="0">
				<tbody>
				<tr>
					<td width="36px" style="text-align: center;"><img
							src="<?php echo REDI_RESTAURANT_PLUGIN_URL ?>img/f_name.png" width="22px" height="22px"
							alt=""/>
					</td>
					<td width="295"><span class="f_H2">Person</span></td>
				</tr>
				</tbody>
			</table>


		</div>
		<div class="f_subbox_data">
			<!--			<div class="f_arrow_back_temp"></div>-->
			<!--			<div class="f_arrow_next_temp"></div>-->

			<table width="350px" border="0" align="center" cellspacing="6" class="f_person_data">
				<tr>
					<td width="33px" class="select">1</td>
					<td width="33px">2</td>
					<td width="33px">3</td>
					<td width="33px">4</td>
					<td width="33px">5</td>
					<td width="33px">6</td>
					<td width="33px">7</td>
					<td width="33px">8</td>
				</tr>
			</table>
		</div>
		</p>
		<br>

		<p>

		<div class="f_subbox_title">
			<table width="335px" border="0" align="center" cellspacing="0">
				<tbody>
				<tr>
					<td width="36px"><img src="<?php echo REDI_RESTAURANT_PLUGIN_URL ?>img/f_calender.png" width="23px"
					                      height="23px" alt=""/></td>
					<td width="293px"><span class="f_H2">Select date</span></td>
				</tr>
				</tbody>
			</table>
		</div>

		<div class="f_subbox_data2">
			<!--			<div class="f_arrow_back_temp"></div>-->
			<!--			<div class="f_arrow_next_temp"></div>-->
			<table width="350px" border="0" align="center" cellspacing="6" class="f_calender_data">
				<tbody>
				<tr>
					<?php foreach ( $dates as $date ): ?>
						<td width="33px" <?php if ($date['selected']): ?>class="select"<?php endif ?>>
							<input type="hidden" value="<?php echo $date['hidden'] ?>">
							<span class="legend"><?php echo $date['month'] ?> </span>
							<br><?php echo $date['day'] ?><br>
							<span class="legend"><?php echo $date['weekday'] ?></span>
						</td>
					<?php endforeach ?>
				</tr>
				</tbody>
			</table>

		</div>
		<br>

		<div id="step1errors" <?php if ( ! isset( $step1['Error'] )): ?>style="display: none;"<?php endif; ?>
		     class="redi-reservation-alert-error redi-reservation-alert">
			<?php if ( isset( $step1['Error'] ) ): ?>
				<?php echo $step1['Error']; ?><br clear="both"/>
			<?php endif; ?>
		</div>
		<br>


		<p>

		<div class="f_subbox_title">
			<table width="335" border="0" align="center" cellspacing="0">
				<tbody>
				<tr>
					<td width="36px"><img src="<?php echo REDI_RESTAURANT_PLUGIN_URL ?>img/f_time.png" width="24px"
					                      height="25px" alt=""/></td>
					<td width="295px"><span class="f_H2">Time</span></td>
				</tr>
				</tbody>
			</table>
		</div>
		<div id="step2busy" <?php if(!$all_busy):?>style="display: none;"<?php endif; ?> class="redi-reservation-alert-error redi-reservation-alert">
			<?php _e('Reservation is not available on selected day. Please select another day.', 'redi-restaurant-reservation');?>
		</div>
		<div class="f_subbox_data3">
			<div class="f_arrow_next_temp2"></div>

			<div class="f_arrow_back_temp2"></div>
			<img id="step1load" style="display: none;" src="<?php echo REDI_RESTAURANT_PLUGIN_URL ?>img/loader1.gif" alt=""/>
			<table width="350px" border="0" align="center" cellspacing="6" class="f_time_data" id="buttons">
				<?php if ( isset( $step1 ) && is_array( $step1 ) && ! isset( $step1['Error'] ) ): ?>
					<?php $current = 0; ?>
					<?php foreach ( $step1 as $available ): ?>

						<?php if ( isset( $available['Availability'] ) && is_array( $available['Availability'] ) ): ?>
							<?php $all_busy = true; ?>

							<?php foreach ( $available['Availability'] as $button ): ?>
								<?php $current ++; ?>
								<?php if($current ==1):?><tr><?php endif?>
									<td class="<?php echo $current ?>" <?php if ( ! $button['Available']): ?>disabled="disabled"<?php endif ?> width="25%">
										<input type="hidden" value="<?php echo $button['StartTimeISO'] ?>"/>
										<?php echo $button['StartTime'] ?></td>
								<?php if($current == 4): $current=0;?></tr><?php endif?>

								<?php if ( $button['Available'] ) {
									$all_busy = false;
								} ?>


							<?php endforeach; ?>
					<?php endif; ?>

					<?php if ( $hidesteps ): ?>
							</span>
						<?php endif ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</table>
		</div>

		<div style="width: 160px; height:40px; margin-top: 30px;">
			<div class="f_btn">NEXT &nbsp;&gt;</div>
		</div>
		</p>
		<p><br>
		</p>
	</div>

</div>




