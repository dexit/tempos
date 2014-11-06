<?php

class BarGraph extends Graph
{
	// Vars

	public $bar_spacing = 8; // Bars vertical spacing.
	public $min_value = 0; // Min bars value.
	public $max_value = 100; // Max bars value.
	public $titles_values_spacing = 16; // Space between titles and values.
	public $values_bars_spacing = 8; // Space between values and bars.
	public $font =  '/usr/share/fonts/truetype/msttcorefonts/verdana.ttf'; // The titles font.
	public $font_size = 10; // The titles font size.
	public $suffix = '%'; // The values suffix.

	// Graph functions

	protected function computeHeight()
	{
		$image = imagecreatetruecolor(1, 1);
		$bar_height = $this->getBarHeight($image);
		imagedestroy($image);

		$height = $this->padding * 2;
		$height += $bar_height * count($this->data['values']);
		$height += $this->bar_spacing * (count($this->data['values']) - 1);

		return max($height, $this->height);
	}

	public function getMimeType()
	{
		return 'image/png';
	}

	protected function renderImage($image)
	{
		$w = $this->getWidth();
		$h = $this->getHeight();

		$background = $this->getColor('background', $image);
		$foreground = $this->getColor('foreground', $image);
		$halftone 	= $this->getColor('halftone', $image);

		$colors = array();
		$shadows = array();

		for ($i = 0; $i < count($this->data['values']); ++$i)
		{
			$r = rand(64, 192);
			$g = rand(64, 192);
			$b = rand(64, 192);

			$shadows[$i] = Graph::htmlColorToColor(sprintf('#%02x%02x%02x', $r, $g, $b), $image);
			$colors[$i] = Graph::htmlColorToColor(sprintf('#%02x%02x%02x', $r + 63, $g + 63, $b + 63), $image);
		}

		// background
		imagefill($image, 0, 0, $background);

		// border
		imagerectangle($image, 0, 0, $w - 1, $h - 1, $foreground);

		// draw bars

		$bar_height = $this->getBarHeight($image);
		$titles_width = 0;
		$y = $this->padding;

		foreach ($this->data['titles'] as $title)
		{
			imagettftext($image, $this->font_size, 0, $this->padding, $y + $bar_height - 4, $foreground, $this->font, $title);
			$box = imagettfbbox($this->font_size, 0, $this->font, $title);

			$tw = $box[2] - $box[0];

			if ($tw > $titles_width)
			{
				$titles_width = $tw;
			}

			$y += $bar_height + $this->bar_spacing;
		}

		$titles_width += $this->padding + $this->titles_values_spacing;
		$values_width = 0;
		$y = $this->padding;

		foreach ($this->data['values'] as $value)
		{
			imagettftext($image, $this->font_size, 0, $titles_width, $y + $bar_height - 4, $halftone, $this->font, $value.$this->suffix);
			$box = imagettfbbox($this->font_size, 0, $this->font, $value.$this->suffix);

			$tw = $box[2] - $box[0];

			if ($tw > $values_width)
			{
				$values_width = $tw;
			}

			$y += $bar_height + $this->bar_spacing;
		}

		$values_width += $this->values_bars_spacing;
		$y = $this->padding;
		$i = 0;

		foreach ($this->data['values'] as $value)
		{
			$x1 = $titles_width + $values_width;
			$y1 = $y;
			$x2 = round(($value * ($w - $this->padding - $titles_width - $values_width)) / $this->max_value) + $titles_width + $values_width;
			$y2 = $y + $bar_height;

			imagefilledrectangle($image, $x1, $y1, $x2, $y2, $shadows[$i]);
			if ($x2 - $x1 > 2) imagefilledrectangle($image, $x1 + 1, $y1 + 1, $x2 - 1, $y2 - 3, $colors[$i]);

			$y += $bar_height + $this->bar_spacing;
			++$i;
		}

		imagepng($image, dirname(__FILE__).'/../../../../../web/images/occupancycache.png');
	}

	protected function getBarHeight($image)
	{
		$height = 0;

		foreach ($this->data['titles'] as $title)
		{
			$box = imagettfbbox($this->font_size, 0, $this->font, $title);

			$th = $box[1] - $box[7];

			if ($th > $height)
			{
				$height = $th;
			}
		}

		return $height + 4;
	}

	// Self functions

	/**
	 * \brief Get the bars titles.
	 *
	 * \return An array containing the titles.
	 */
	public function getTitles()
	{
		if (array_key_exists('titles', $this->data))
		{
			return $this->data['titles'];
		} else
		{
			return array();
		}
	}

	/**
	 * \brief Set the bars titles.
	 *
	 * \param $titles An array containing the titles.
	 */
	public function setTitles($titles)
	{
		if (!is_array($titles))
		{
			throw new Exception('$titles must be an array.');
		}

		$this->data['titles'] = $titles;
	}
	
	/**
	 * \brief Get the bars values.
	 *
	 * \return An array containing the values.
	 */
	public function getValues()
	{
		if (array_key_exists('values', $this->data))
		{
			return $this->data['values'];
		} else
		{
			return array();
		}
	}

	/**
	 * \brief Set the bars values.
	 *
	 * \param $titles An array containing the values.
	 */
	public function setValues($values)
	{
		if (!is_array($values))
		{
			throw new Exception('$values must be an array.');
		}

		$this->data['values'] = $values;
	}

	/**
	 * \brief Set the min value.
	 *
	 * \param value The value.
	 */
	public function setMinValue($value)
	{
		$this->min_value = $value;
	}

	/**
	 * \brief Set the max value.
	 *
	 * \param value The value.
	 */
	public function setMaxValue($value)
	{
		$this->max_value = $value;
	}
}
