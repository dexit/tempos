<noscript>
<?php include_partial('tools/messageBox', array('class' => 'warning', 'title' => __('Javascript disabled'), 'msg' => __('JavaScript is disabled. Deletion function disabled due to security measures.'), 'showImg' => true)); ?>
</noscript>

<?php $params_uri = '&roomId='.$room->getId(); ?>
<?php $uri = 'reservation/index'.'?displayPeriod=week'.$params_uri; ?>

<h2 class="weekNo">
	<?php echo link_to('< '.__('Previous week'), $uri.'&date='.date('Y-m-d', strtotime(date('Y-m-d', $date).'-1 week'))); ?>
	<?php echo link_to(__('Next week').' >', $uri.'&date='.date('Y-m-d', strtotime(date('Y-m-d', $date).'+1 week'))); ?>
	<?php echo __('Week #%week_number% - %year%', array('%week_number%' => strftime('%V', $date), '%year%' => date('Y', $date))); ?>
	<?php echo display_planning_calendar($uri); ?>
</h2>

<?php
	
	/* $test = 0;
	foreach ($reservation_list as $res)
	{
		var_dump($res);
		print $res->hasDaughters() ? 'OUI<br />': 'NON<br />';
		$test ++;
		print '[--- '.$res->getId().' ---] '.$res.'<br/>';
	}
	print $test; */
	
?>

<table class="planning">
	<thead>
		<tr>
			<th class="empty"></th>
			<th class="hide"></th>
			<?php 
			$open_days = array();
			for ($day = 0; $day < 7; ++$day){ ?>
				<?php 
				if ($room->isOpenDay($day)){ ?>
					<th><?php echo __('%week_day% - %date%', array('%week_day%' => Dayperiod::dayOfWeekToShortName($day), '%date%' => strftime('%d/%m', strtotime(date('Y-m-d', $date)." +".($day - Dayperiod::toWeekDay($date) + 1)." day")))); ?></th>
					<?php $open_days[] = $day+1;
				}
			} ?>
		</tr>
	</thead>
	<tbody>
		<?php 
		$startIndex = $room->getOverallStartIndex();
		$stopIndex = $room->getOverallStopIndex();
		$minimum_date = $activity->getMinimumDate();
		
		$printTitle = ConfigurationHelper::getParameter('Print', 'print_title');
		$printOptions = array(
			'reserved_by' => ConfigurationHelper::getParameter('Print', 'print_reserved_by'),
			'reserved_for' => ConfigurationHelper::getParameter('Print', 'print_reserved_for'),
			'reason' => ConfigurationHelper::getParameter('Print', 'print_reason'),
			'time' => ConfigurationHelper::getParameter('Print', 'print_time'),
			'duration' => ConfigurationHelper::getParameter('Print', 'print_duration'),
			'custom1' => ConfigurationHelper::getParameter('Print', 'print_custom_field1'),
			'custom2' => ConfigurationHelper::getParameter('Print', 'print_custom_field2'),
			'custom3' => ConfigurationHelper::getParameter('Print', 'print_custom_field3'),
			'status' => ConfigurationHelper::getParameter('Print', 'print_status'),
			'profile' => ConfigurationHelper::getParameter('Print', 'print_profile')
		);
					
		$tabRes = array(array());

		$free_field_1_name = ConfigurationHelper::getParameter('Rename', 'reservation_custom_field_1');
		$free_field_2_name = ConfigurationHelper::getParameter('Rename', 'reservation_custom_field_2');
		$free_field_3_name = ConfigurationHelper::getParameter('Rename', 'reservation_custom_field_3');
		
		// First column
		for($i = $startIndex; $i < $stopIndex; ++$i)
		{
			$tst = mktime($i / 2, ($i % 2) * 30);
					
			if ($i % 2 == 0){
				if ($i != ($stopIndex - 1)){
					$tabRes[0][$i] = '<th rowspan="1" class="hour">'.strftime('%H:%M', $tst).'</th><td class="hide"></td>';
				} else {
					$tabRes[0][$i] = '<th rowspan="1" class="half">'.strftime('%H:%M', $tst).'</th><td class="hide"></td>';
				}
			} else {
				if ($i == $startIndex){
					$tabRes[0][$i] = '<th rowspan="1" class="half">'.strftime('%H:%M', $tst).'</th><td class="hide"></td>';
				} else {
					$tabRes[0][$i] = '<th rowspan="1"></th><td class="hide"></td>';
				}
			}
		}
		
		$nextReservation = true;
		$j = 0;
		$index = 0;
		
		// Every opening days
		foreach ($open_days as $day)
		{
			for($i = $startIndex; $i < $stopIndex; ++$i)
			{
				//print "$day-$i<br/>";
				$compteur = 0;
				$tst = strtotime(" + ".($day - 1)." day + ".($i * 30)." minute ", $weekStart);
				$past = (($tst < time()) || (isset($activity) ? ($tst < $minimum_date) : false));
				$is_open = ($room->isOpen($tst));
				$toofar = isset($activity) ? ($tst >= $person->getMaximumDate($activity->getId(), $room->getId())) || !$person->hasSubscription($activity->getId(), $room->getId(), $tst) : true;

				$content = '';

				if (!isset($tabRes[$day][$i]))
				{
					if ($nextReservation)
					{
						// Si l'index est inférieur à 0, il n'y a plus de réservations
						if ($index >= 0)
						{
							if (isset($reservation_list[$j]))
							{
								$reservation = $reservation_list[$j];
								$index = $reservation->getDateToIndex();
								$nextReservation = false;
								
								// Position de la réservation dans la liste de réservations
								$j++;
							} else
							{
								$index = -1;
							}
						}
					}

					// print "$day - $i<br/>Index: ".$index.'<br/><br/>';
					// print date('Y-m-d H:i:s', $tst).'<br/>';
					// print date('Y-m-d H:i:s', strtotime($reservation->getDate())).'<br/>';
					
					// print 'timestamp:'.$reservation->matchTimestamp($tst).'<br/>';
					
					if (isset($reservation))
					{
						if ($reservation->matchTimestamp($tst))
						{
							if ($index == $i)
							{
								$durationIndex = $reservation->getDurationToIndex();
								$plusDay = floor(($i + $durationIndex) / $stopIndex);
								$diff = ($stopIndex - $i);
								
								$b = 1;
								
								if(($durationIndex + $i) <= $stopIndex)
								{
									$nextReservation = true;
								}
								
								$reservation_past = $reservation->isPast();
								$reservation_editable = $reservation->isEditable();
								$can_edit_reservation = ($is_admin && $reservation_editable) || $realPerson->canEditReservation($reservation) || $person->canEditReservation($reservation);
								
								// <td>
								$content .= '<td class="reservation ';
								$content .= $reservation_past ? 'past' : '';
								$content .= $can_edit_reservation ? ' draggable"' : '"';
								$content .= ' rowspan="'.floor($reservation->getDuration($tst) / 30).'">';
								
								/* Bloque les demi-heures suivantes selon la durée de cette réservation
								
								On parcourt la réservation index par index (demi-heure par demi-heure)
								tant qu'on atteint pas la fin de sa durée ET qu'on est pas à la fin de la journée
								*/
								while ($b < $durationIndex && ($b + $i) < $stopIndex)
								{
									$tabRes[$day][$i+$b] = '<td class="hide"></td>';
									$b++;
								}
								
								$compteur = $b - 1;
								
								// <h1>
								$content .= '<h1';
								$content .= $reservation_past ? '>':' style="background-color: '.$reservation->getActivity()->getColor().'">';
											
								// Icône suppression si l'utilisateur est admin ET JavaScript est activé
								$can_delete_reservation = ($is_admin && $reservation_editable) || $realPerson->canDeleteReservation($reservation) || $person->canDeleteReservation($reservation);
								if ($can_delete_reservation)
								{
									if_javascript();
										if ($reservation->hasParent() || $reservation->hasDaughters())
										{
											$content .= link_to(image_tag('/sf/sf_admin/images/cancel.png', array('alt' => __('Delete reservation'))), 'reservationdelete/confirm?id='.$reservation->getId(), array('class' => 'action'));
										} else
										{
											$content .= link_to(image_tag('/sf/sf_admin/images/cancel.png', array('alt' => __('Delete reservation'))), 'reservation/delete?id='.$reservation->getId().$params_uri, array('class' => 'action', 'method' => 'delete', 'confirm' => __('Are you sure ?')));
										}
									end_if_javascript();
								}
								
								$content .= $reservation->getActivity()->getName().'</h1>';
								$content .= '<h1 class="print_only" style="color: '.$reservation->getActivity()->getColor().'">'
											.$reservation->getActivity()->getName().'</h1>';
								
								// Icône édition
								if ($can_edit_reservation)
								{
									$content .= '<div class="no_print">'.link_to(image_tag('/sf/sf_admin/images/edit.png', 
												array('alt' => __('Edit reservation'))), 'reservation/edit?id='.$reservation->getId(), 
												array('class' => 'action')).'<span>'.__('Edit reservation').'</span><br/>';
									if (!$reservation->hasParent() && !$reservation->hasDaughters())
									{
										$content .= link_to(image_tag('/sf/sf_admin/images/reset.png',
													array('alt' => __('Repeat reservation'))), 'reservation/repeat?id='.$reservation->getId(),
													array('class' => 'action')).'<span>'.__('Repeat reservation').'</span>';
									} else
									{
										$content .= '<div class="blue">'.__('This reservation is part of repeat reservations').'</div>';
									}
									$content .= '</div>';
								}
								
								// Icône voir
								if ($reservation_past)
								{
									$content .= '<div class="no_print">'.link_to(image_tag('/sf/sf_admin/images/list.png',
												array('alt' => __('View reservation'))), 'reservation/view?id='.$reservation->getId(),
												array('class' => 'action')).'<span>'.__('View reservation').'</span></div>';
								}
								
								// Icône message
								$can_send_message = $realPerson->canSendMessage($reservation);
								
								if ($can_send_message)
								{
									$content .= '<div class="no_print">'.link_to(image_tag('/sf/sf_admin/images/list.png',
												array('alt' => __('Send message'))), 'reservation/sendMessage?id='.$reservation->getId(),
												array('class' => 'action')).'<span>'.__('Send message').'</span></div>';
								}
								
								// <dl>
								$content .= '<dl>';
								$can_see_reservation = $is_admin || $realPerson->canSeeReservationDetails($reservation) || $person->canSeeReservationDetails($reservation);
								
								if ($can_see_reservation)
								{
									// <dt> Titre réservé par
									$content .= '<dt><div';
									$content .= ($printTitle && $printOptions['reserved_by']) ? '>' : ' class="no_print">';
									$content .= __('Reserved by: ').'</div></dt>';
								
									// </dt>
									
									// <dd> Contenu réservé par
									$content .= '<dd><div';
									$content .= $printOptions['reserved_by'] ? ' class="no_print">' : '>';
									$content .= $reservation->getUserFullName().'</div></dd>';
									// </dd>
									
									if (!is_null($reservation->getUsergroup()))
									{
										// <dt> Titre réservé pour
										$content .= '<dt><div';
										$content .= ($printTitle && $printOptions['reserved_for']) ? '>' : ' class="no_print">';
										$content .= __('Reserved for: ').'</div></dt>';
										// </dt>
										
										// <dd> Contenu réservé pour
										$content .= '<dd><strong>';
										
										$ug = $reservation->getUsergroup();
										$resa_group_name = $ug->getName();
										
										$content .= '<div';
										$content .= $printOptions['reserved_for'] ? '>':' class="no_print">';
										$content .= $resa_group_name.'</div></strong></dd>';
										// </dd>
									}
								}
								
								if (!is_null($reservation->getReservationreason()))
								{
									// <dt> Titre raison
									$content .= '<dt><div';
									$content .= ($printTitle && $printOptions['reason']) ? '>' : ' class="no_print">';
									$content .= __('Reason: ').'</div></dt>';
									// </dt>
									
									// <dd> Contenu raison
									$content .= '<dd><div';
									$content .= $printOptions['reason'] ? '>' : ' class="no_print">';
									$content .= $reservation->getReservationreason()->getName().'</div></dd>';
									// </dd>
								}
								
								// <dt> Titre heure
								$content .= '<dt><div';
								$content .= ($printTitle && $printOptions['time']) ? '>' : ' class="no_print">';
								$content .= __('Time: ').'</dt></div>';
								// </dt>
								
								// <dd> Contenu heure
								$content .= '<dd><div';
								$content .= $printOptions['time'] ? '>' : ' class="no_print">';
								$content .= __('%start_time%-%stop_time%', array(
												'%start_time%' => $reservation->getDate('H:i'),
												'%stop_time%' => $reservation->getStopDate('H:i'),
											)).'</div></dd>';
								// </dd>
								
								// <dt> Titre durée
								$content .= '<dt><div';
								$content .= ($printTitle && $printOptions['duration']) ? '>' : ' class="no_print">';
								$content .= __('Duration: ').'</div></dt>';
								// </dt>
								
								// <dd> Contenu durée
								$duration = $reservation->getDuration();
								$hourDuration = floor($duration / 60);
								$minuteDuration = $duration % 60;
								
								$content .= '<dd><div';
								$content .= $printOptions['duration'] ? '>' : ' class="no_print">';
								
								if ($hourDuration == 0)
								{
									$content .= __('%duration% minute(s)', array('%duration%' => $minuteDuration));
								} else
								{
									$strDuration = $hourDuration.($minuteDuration != 0 ? ':'.$minuteDuration : '');
									$content .= __('%duration% hour(s)', array('%duration%' => $strDuration));
								}
								
								$content .= '</div></dd>';
								// </dd>
								
								// Custom1
								$custom1 = $reservation->getCustom1();
								if (!empty($free_field_1_name) && !empty($custom1))
								{
									// <dt> Titre custom1
									$content .= '<dt><div';
									$content .= ($printTitle && $printOptions['custom1']) ? '>' : ' class="no_print">';
									$content .= $free_field_1_name.' :</div></dt>';
									// </dt>
									
									// <dd> Contenu custom1
									$content .= '<dd><div';
									$content .= $printOptions['custom1'] ? '>' : ' class="no_print">';
									$content .= $custom1.'</div></dd>';
									// </dd>
								}
								
								// Custom2
								$custom2 = $reservation->getCustom2();
								if (!empty($free_field_2_name) && !empty($custom2))
								{
									// <dt> Titre custom2
									$content .= '<dt><div';
									$content .= ($printTitle && $printOptions['custom2']) ? '>' : ' class="no_print">';
									$content .= $free_field_2_name.' :</div></dt>';
									// </dt>
									
									// <dd> Contenu custom2
									$content .= '<dd><div';
									$content .= $printOptions['custom2'] ? '>' : ' class="no_print">';
									$content .= $custom2.'</div></dd>';
									// </dd>
								}
								
								// Custom3
								$custom3 = $reservation->getCustom3();
								if (!empty($free_field_3_name) && !empty($custom3))
								{
									// <dt> Titre custom3
									$content .= '<dt><div';
									$content .= ($printTitle && $printOptions['custom3']) ? '>' : ' class="no_print">';
									$content .= $free_field_3_name.' :</div></dt>';
									// </dt>
									
									// <dd> Contenu custom3
									$content .= '<dd><div';
									$content .= $printOptions['custom3'] ? '>' : ' class="no_print">';
									$content .= $custom3.'</div></dd>';
									// </dd>
								}
								
								if ($is_admin)
								{
									if (!$reservation->isOld())
									{
										// <dt> Titre statut
										$content .= '<dt><div';
										$content .= ($printTitle && $printOptions['status']) ? '>' : ' class="no_print">';
										$content .= __('Status').'</div></dt>';
										// </dt>
										
										// <dd> Contenu statut
										$content .= '<dd class="status"><div';
										$content .= $printOptions['status'] ? '>' : ' class="no_print">';
										
										if ($reservation->isIdle())
										{
											$content .= '<span class="idle">'.__('Idle').'</span>';
										} elseif ($reservation->isSynchronized())
										{
											$content .= '<span class="synchronized">'.__('Synchronized with physical access').'</span>';
										} elseif ($reservation->isBlocked())
										{
											$content .= '<span class="blocked">'.__('Blocked: user(s) can access room').'</span>';
										}
										$content .= '</div></dd>';
										// </dd>
									}
									
									// <dt> Titre accès physique
									$content .= '<dt><div';
									$content .= ($printTitle && $printOptions['profile']) ? '>' : ' class="no_print">';
									$content .= __('Physical access').' :</div></dt>';
									// </dt>
									
									// <dd> Contenu accès physique
									$content .= '<dd><div';
									$content .= $printOptions['profile'] ? '>' : ' class="no_print">';
									$content .= $reservation->getRoomprofile()->getName().'</div></dd>';
									// </dd>
							
									// <dd> Réservation oubliée
									if ($reservation->isForgotten())
									{
										$content .= '<dd><div><span class="forgotten">'.__('Forgotten !').'</span></div></dd>';
									}
									// </dd>
									
									// <dd> Réservation avec erreur
									if ($reservation->isInError())
									{
										$content .= '<dd><div><span class="forgotten">'.__('Physical access reports error !').'</span></div></dd>';
									}
									// </dl>
									
									// </td>
									$content .= '</dl></td>';
								}
							} else
							{
								// Si le jour commence par une réservation étendue
								$durationIndex = $reservation->getDurationToIndex();
								$stopDate = $reservation->getDateToIndex($reservation->getStopDate());			
								$reservation_past = $reservation->isPast();
								
								//print 'DATE INDEX:'.$reservation->getDateToIndex().' DUREE INDEX:'.$reservation->getDurationToIndex().'<br/>';
								//print $stopIndex.'<br/>';
								
								$nextReservation = true;
								$b=0;
								
								if (date('Y-m-d', $tst) == date('Y-m-d', strtotime($reservation->getStopDate())))
								{
									while ($b < $stopDate)
									{
										if ($b == 0)
										{
											//print 'tab['.($day).']['.$b.'] = reservation rspan='.$stopDate.'<br/>';
											$tabRes[$day][$b] = '<td class="reservation'.($reservation_past ? ' past':'').'" rowspan="'.($stopDate).'"></td>';
										} else
										{
											//print 'tab['.($day).']['.$b.'] = hide<br/>';
											$tabRes[$day][$b] = '<td class="hide"></td>';
										}
										$b++;
									}
								} else
								{
									for ($b; $b < $stopIndex; $b++)
									{
										if ($b == 0)
										{
											//print 'tab['.($day).']['.$b.'] = reservation rspan='.$stopIndex.'<br/>';
											$tabRes[$day][$b] = '<td class="reservation" rowspan="'.($stopIndex).'"></td>';
										} else
										{
											//print 'tab['.($day).']['.$b.'] = hide<br/>';
											$tabRes[$day][$b] = '<td class="hide"></td>';
										}
									}
								}
								
								$savei = $i;
								$compteur = $b - 1;
							}
						} else
						{
							$bookable = isset($activity) && (!$past) && (!$toofar) && ($is_open);
							$classes = array();
							
							if (strftime('%Y-%m-%d', $tst) == strftime('%Y-%m-%d')) $classes[] = 'now';
							if ($past) $classes[] = 'past';
							if (!$past && $toofar) $classes[] = 'toofar';
							if (!$is_open) $classes[] = 'closed';
							if ($bookable) $classes[] = 'tselectable'; 
							
							$content .= '<td';
							$content .= $bookable ? ' id="book-'.$day.'-'.$i.'"' : '';
							$content .= ' class="'.implode(' ', $classes).'">';
							
							if ($bookable)
							{
								$content .= link_to(__('Add a new reservation'), 'reservation/new?roomId='.$room->getId().'&date='.strftime('%Y-%m-%d %H:%M', $tst));
								$content .= '<td class="print_only"></td>';
							}
						}
					} else
					{
						$bookable = isset($activity) && (!$past) && (!$toofar) && ($is_open);
						$classes = array();
						
						if ($i % 2 == 0) $classes[] = 'hour';
						if (strftime('%Y-%m-%d', $tst) == strftime('%Y-%m-%d')) $classes[] = 'now';
						if ($past) $classes[] = 'past';
						if (!$past && $toofar) $classes[] = 'toofar';
						if (!$is_open) $classes[] = 'closed';
						if ($bookable) $classes[] = 'tselectable'; 
						
						$content .= '<td';
						$content .= $bookable ? ' id="book-'.$day.'-'.$i.'"' : '';
						$content .= ' class="'.implode(' ', $classes).'">';
						
						if ($bookable)
						{
							$content .= link_to(__('Add a new reservation'), 'reservation/new?roomId='.$room->getId().'&date='.strftime('%Y-%m-%d %H:%M', $tst));
							$content .= '<td class="print_only"></td>';
						}
					}
					
					if (!isset($tabRes[$day][$i]))
					{
						$tabRes[$day][$i] = $content;
					}
					
					$i += $compteur;					
				}
			}
		}
		
		// Affichage du tableau
		for($i = $startIndex; $i < $stopIndex; ++$i)
		{
			echo '<tr>'.$tabRes[0][$i];
			foreach ($open_days as $day)
			{
				echo $tabRes[$day][$i];
			}
			echo '</tr>';
		}		
		//var_dump($tabRes);
		?>
	</tbody>
</table>