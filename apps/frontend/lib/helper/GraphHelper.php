<?php

function graph_bar($titles, $values, $min = 0, $max = 100, $width = 800, $height = 600)
{
	return sprintf(
		'<img src="%s" alt="graph" />',
		url_for_graph('bar', 
			array(
				'titles' => $titles,
				'values' => $values,
				'min' => $min,
				'max' => $max,
				'width' => $width,
				'height' => $height,
			)
		)
	);
}

function url_for_graph($type, $attributes)
{
	if (!empty($attributes))
	{
		$values = array();

		foreach ($attributes as $attribute => $value)
		{
			$values[] = sprintf('%s=%s', $attribute, encode_value($value));
		}
		
		$attributes_str = '?'.implode('&', $values);
	} else
	{
		$attributes_str = null;
	}

	return url_for(sprintf('graph/%s%s', $type, $attributes_str));
}

function encode_value($value)
{
	if (is_array($value))
	{
		return urlencode(json_encode($value));
	} else
	{
		return urlencode($value);
	}
}
