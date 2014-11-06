<?php

function display_planning_calendar($uri_dest = "")
{
	$ret = "";
	$ret .= '<input type="hidden" id="calendar_datepicker" />';
	$ret .= '<script type="text/javascript">';
	$ret .= '	$(function() {';
	$ret .= '		$("#calendar_datepicker").datepicker({';
    $ret .= '			buttonImage: \'/images/calendar.gif\',';
    $ret .= '			buttonImageOnly: true,';
	$ret .= '			showButtonPanel: true,';
    $ret .= '			showOn: \'both\',';
    $ret .= '			onSelect: function(dateText, inst) {';
	$ret .= '				var dateTab = dateText.split(\'/\');';
	$ret .= '				var url = \''.url_for($uri_dest).'\';'; 
	$ret .= '				url += \'/date/\' + dateTab[2] + \'-\' + dateTab[1] + \'-\' + dateTab[0];';
    $ret .= '				window.location = url;';
	$ret .= '			}';
    $ret .= '		})';
	$ret .= '	});';
	$ret .= '</script>';

	return $ret;
}

function resume_text($text, $resume_class = "resume")
{
	$suffix = sfConfig::get('app_resume_suffix');
	$max_size = sfConfig::get('app_resume_max_size');
	$max_text_size = $max_size - strlen($suffix);
	$rtext = rtrim($text);

	if (strlen($rtext) > $max_text_size)
	{
		return '<span class="'.$resume_class.'" title="'.$text.'">'.substr($rtext, 0, $max_text_size).$suffix.'</span>';
	} else
	{
		return $text;
	}
}

function color_square($color = "#fffff", $text = null)
{
	return '<span class="square" style="background-color: '.$color.'">'.$text.'</span>';
}

function color_dot($color = "#fffff")
{
	return '<span class="dot" style="background-color: '.$color.'"></span>';
}

function sort_link($module, $action, $column, $title, $sort_direction, $sort_column = null, $params = null)
{
	$param_str = '';

	if (is_array($params))
	{
		foreach ($params as $key => $value)
		{
			$param_str .= "&$key=$value"; 
		}
	}

	if ($sort_column == $column)
	{
		$style = ($sort_direction == 'up' ? 'sorted-up' : 'sorted-down');
		$sort_direction = ($sort_direction != 'up' ? 'up' : 'down');
		return link_to($title, "$module/$action?sort_column=$column&sort_direction=".$sort_direction.$param_str, array('class' => 'sort '.$style));
	} else
	{
		$sort_direction = ($sort_direction == 'up' ? 'up' : 'down');
		return link_to($title, "$module/$action?sort_column=$column&sort_direction=".$sort_direction.$param_str, array('class' => 'sort'));
	}
}

function block_item($title, $subitems)
{
	$output = '<li>';
	$output .= sprintf('<h3>%s</h3>', $title);
	
	foreach ($subitems as $subitem)
	{
		if (!is_null($subitem) && !empty($subitem))
		{
			$output .= sprintf('<p>%s</p>', $subitem);
		}
	}
	$output .= '</li>';

	return $output;
}

function definition_list($items)
{
	if (count($items) == 0)
	{
		return null;
	}
	
	$output = '<dl>';

	foreach ($items as $text => $definition)
	{
		if (!empty($definition) && !empty($text))
		{
			$output .= '<dt>'.$text.'</dt>';
			$output .= '<dd>'.$definition.'</dd>';
		}
	}

	$output .= '</dl>';

	return $output;
}

function id_list($items)
{
	$array = array();

	foreach ($items as $item)
	{
		$array[] = $item->getId();
	}

	return implode(',', $array);
}

function button_link_to($title, $action, $target = "_self")
{
	$form = new sfForm();

	return sprintf(
		'<form action="%s" method="post" target="%s">'.
			'<input type="hidden" name="%s" value="%s" />'.
			'<input type="submit" value="%s" />'.
		'</form>',
		url_for($action),
		$target,
		$form->getCSRFFieldName(),
		$form->getCSRFToken(),
		__($title)
	);
}

function availability_legend_item($availabilityValue, $text)
{
	$classes = availability_classes($availabilityValue);

	return sprintf('<dt><span class="%s"></span></dt><dd>%s</dd>', implode(' ', $classes), $text);
}

function availability_day_cell($id = null, $availability, $activityId, $texts, $printView, $module = 'reservation', $action = 'new')
{
	$content = '';
	$url = $module.'/'.$action;
	$tst = $availability->getRaw('timestamp');
	$value = $availability->getRaw('value');
	$room = $availability->getRaw('room');
	$classes = availability_classes($value);
	$dayprint = array('complete' => 'X', 'free' => 'O', 'past' => '~', 'toofar' => '~');

	if (!$printView) {
		switch ($value)
		{
			case RoomPeer::COMPLETE:
			{
				$content = sprintf('<span class="hidden">%s</span>', $texts[$value]);
				break;
			}
			case RoomPeer::OCCUPIED:
			{
				$class[] = 'tselectable';
				$content = link_to($texts[$value], $url.'?date='.date('Y-m-d H:i:s', $tst).'&roomId='.$room->getId());
				break;
			}
			case RoomPeer::FREE:
			{
				$classes[] = 'tselectable';
				$content = link_to($texts[$value], $url.'?date='.date('Y-m-d H:i:s', $tst).'&roomId='.$room->getId());
				break;
			}
			case RoomPeer::PAST:
			{
				$content = sprintf('<span class="hidden">%s</span>', $texts[$value]);
				break;
			}
			case RoomPeer::TOOFAR:
			{
				$content = sprintf('<span class="hidden">%s</span>', $texts[$value]);
				break;
			}
		}
		return day_cell($classes, $content, $printView, $id);
	} else {
	        switch ($value)
        	{
                	case RoomPeer::COMPLETE:
                       	{
                               	$content = sprintf('X');
                                break;
       	                }
	               	case RoomPeer::OCCUPIED:
                       	{
                               	$content = sprintf('C');
                                break;
       	                }
                	case RoomPeer::FREE:
                       	{
                               	$content = sprintf('O');
                                break;
       	                }
                	case RoomPeer::PAST:
                       	{
                               	$content = sprintf('~');
                                break;
       	                }
	               	case RoomPeer::TOOFAR:
                       	{
                               	$content = sprintf('~');
	 	                break;
                        }
        	}
		return day_cell($classes, $content, $printView);
	}
}

function day_cell($classes, $content, $printView, $id = null)
{
	if (!$printView) {
		if (!is_null($id))
        	{
	       	        return sprintf('<td id="%s" class="%s no_print">%s</td>', $id, implode(' ', $classes), $content);
      		} else
	       	{
       		        return sprintf('<td class="%s no_print">%s</td>', implode(' ', $classes), $content);
	       	}
	} else {
		return sprintf('<td class="%s print_only">%s</td>', implode(' ', $classes), $content);
	}
}

function availability_week_cell($availability, $activityId, $texts, $module = 'home', $action = 'ganttIndex')
{
	$rooms = '';
	$content = '';
	$url = $module.'/'.$action;
	$tst = $availability->getRaw('timestamp');
	$value = $availability->getRaw('value');
	$classes = availability_classes($value);
        $textprint = array ('complete' => 'X', 'occupied' => 'C', 'free' => 'O', 'past' => '~', 'toofar' => '~');

	switch ($value)
	{
		case RoomPeer::COMPLETE:
			{
				$content = sprintf('<span class="hidden">%s</span>', $texts[$value]);
				break;
			}
		case RoomPeer::OCCUPIED:
			{
				$rooms = implode(',', $availability->getRaw('rooms'));
				$content = link_to($texts[$value], $url.'?date='.date('Y-m-d H:i:s', $tst).'&activityId='.$activityId.'&rooms='.$rooms.'&autobook=');
				break;
			}
		case RoomPeer::FREE:
			{
				$rooms = implode(',', $availability->getRaw('rooms'));
				$content = link_to($texts[$value], $url.'?date='.date('Y-m-d H:i:s', $tst).'&activityId='.$activityId.'&rooms='.$rooms.'&autobook=');
				break;
			}
		case RoomPeer::PAST:
			{
				$content = sprintf('<span class="hidden">%s</span>', $texts[$value]);
				break;
			}
		case RoomPeer::TOOFAR:
			{
				$content = sprintf('<span class="hidden">%s</span>', $texts[$value]);
				break;
			}
	}
	return week_cell($classes, $content, $textprint);
}

function week_cell($classes, $content, $textprint)
{
	return sprintf('<td class="%s no_print">%s</td><td class="%s print_only">%s</td>', implode(' ', $classes), $content, implode(' ', $classes), $textprint[implode(' ', $classes)]);
}

function availability_month_cell($availability, $activityId, $texts, $module = 'home', $action = 'ganttIndex')
{
	$content = '';
	$url = $module.'/'.$action;
	$tst = $availability->getRaw('timestamp');
	$value = $availability->getRaw('value');
	$classes = availability_classes($value);
	$dayStr = strftime('%#d', $availability->getRaw('timestamp'));

	switch ($value)
	{
		case RoomPeer::COMPLETE:
			{
				$content = sprintf('<span class="hidden">%s</span>', $texts[$value]);
				break;
			}
		case RoomPeer::OCCUPIED:
			{
				$rooms = implode(',', $availability->getRaw('rooms'));
				$content = link_to($texts[$value], $url.'?date='.date('Y-m-d', $tst).'&activityId='.$activityId.'&rooms='.$rooms);
				break;
			}
		case RoomPeer::FREE:
			{
				$rooms = implode(',', $availability->getRaw('rooms'));
				$content = link_to($texts[$value], $url.'?date='.date('Y-m-d', $tst).'&activityId='.$activityId.'&rooms='.$rooms);
				break;
			}
		case RoomPeer::PAST:
			{
				$content = sprintf('<span class="hidden">%s</span>', $texts[$value]);
				break;
			}
		case RoomPeer::TOOFAR:
			{
				$content = sprintf('<span class="hidden">%s</span>', $texts[$value]);
				break;
			}
	}

	return month_cell($classes, $dayStr, $content);
}

function month_cell($classes, $dayStr, $content)
{
	return sprintf('<td class="%s"><h4>%s</h4><p>%s</p></td>', implode(' ', $classes), $dayStr, $content);
}

function availability_classes($availabilityValue)
{
	$classes = array();

	switch ($availabilityValue)
	{
		case RoomPeer::COMPLETE:
			{
				$classes[] = 'complete';
				break;
			}
		case RoomPeer::OCCUPIED:
			{
				$classes[] = 'occupied';
				break;
			}
		case RoomPeer::FREE:
			{
				$classes[] = 'free';
				break;
			}
		case RoomPeer::PAST:
			{
				$classes[] = 'past';
				break;
			}
		case RoomPeer::TOOFAR:
			{
				$classes[] = 'toofar';
				break;
			}
	}

	return $classes;
}

function enquote_string($value)
{
	if (is_string($value))
	{
		$value = html_entity_decode($value, ENT_QUOTES);
		$value = preg_replace('/"/', '""', $value);
		$value = sprintf('"%s"', $value);
	}

	return $value;
}

function csv_line($values, $include_EOL = true, $delimiter = ';')
{
	$values = array_map('enquote_string', $values);
	return implode($delimiter, $values).($include_EOL ? "\n" : '');
}

function filter_values($values, $fields)
{
	if (!is_array($fields))
	{
		if (!is_null($fields))
		{
			$fields = array($fields);
		} else
		{
			return $values;
		}
	}

	$newvalues = array();

	foreach ($fields as $field)
	{
		if (array_key_exists($field, $values))
		{
			$newvalues[$field] = $values[$field];
		}
	}

	return $newvalues;
}

function utf8_bom()
{
	$bom = sprintf("%c%c%c", 0xEF, 0xBB, 0xBF);

	return $bom;
}

function txt_table($table)
{
	$result = '';

	$columns = txt_table_columns_length($table);
	$array = array_values($table);

	foreach ($table as $line => $row)
	{
		$row = array_values($row);

		if ($line == 0)
		{
			$cnt = 0;

			foreach ($row as $index => $cell)
			{
				$result .= str_pad($cell, $columns[$index], " ", STR_PAD_BOTH);

				if ($index < count($row) - 1)
				{
					$result .= '|';
					$cnt += 1;
				}

				$cnt += $columns[$index];
			}

			$result .= "\n";
			$result .= str_repeat('-', $cnt)."\n";
		} else
		{
			foreach ($row as $index => $cell)
			{
				$result .= str_pad($cell, $columns[$index], " ", STR_PAD_LEFT);

				if ($index < count($row) - 1)
				{
					$result .= '|';
				}
			}

			$result .= "\n";
		}
	}

	return $result;
}

function txt_table_columns_length($table)
{
	$columns = array();

	foreach ($table as $row)
	{
		foreach ($row as $index => $cell)
		{
			if (!array_key_exists($index, $columns) || ($columns[$index] < strlen($cell)))
			{
				$columns[$index] = strlen($cell);
			}
		}
	}

	return $columns;
}

?>
