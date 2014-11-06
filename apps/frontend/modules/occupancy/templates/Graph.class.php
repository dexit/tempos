<?php

abstract class Graph
{
	protected $width = null;
	protected $height = null;
	protected $colors = array();
	protected $data = null;

	/* Defaults, not in constructor */
	protected $padding = 8; /* The internal padding, in pixels */

	/**
	 * \brief Creates a graph.
	 *
	 * \param $width The width of the graph to create. Default is 800 pixels.
	 * \param $height The minimum height of the graph to create. A null value (the default) indicates that the graph must compute its size automatically.
	 */
	public function __construct($width = 800, $height = null)
	{
		$this->width = $width;
		$this->height = $height;

		$this->setColor('background', '#ffffff');
		$this->setColor('foreground', '#000000');
		$this->setColor('halftone', 	'#666666');
	}

	/**
	 * \brief Get the raw data.
	 *
	 * \return The raw data.
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * \brief Set the raw data.
	 *
	 * \param $data The data to set. 
	 */
	public function setData($data)
	{
		$this->data = $data;
	}

	/**
	 * \brief Copy the data from another Graph.
	 *
	 * \param $graph The other graph to copy data from.
	 */
	public function copyFrom(Graph $graph)
	{
		$this->setData($graph->getData());
	}

	/**
	 * \brief Get the width of the graph.
	 *
	 * \return The width of the graph.
	 */
	public function getWidth()
	{
		return $this->width;
	}

	/**
	 * \brief Get the (eventually computed) height of the graph.
	 *
	 * \return The height of the graph, or null if the height must be computed but no data has been provided.
	 */
	public function getHeight()
	{
		if (!is_null($this->data))
		{
			return $this->computeHeight();
		} else
		{
			return $this->height;
		}
	}

	/**
	 * \brief Get a color from the color palette.
	 *
	 * \param $key The key of the color to get.
	 * \param $image A non-mandatory image resource object. Default is null.
	 * \return A string representing the color if $image is null, a color identifier otherwise.
	 */
	public function getColor($key, $image = null)
	{
		if (!array_key_exists($key, $this->colors))
		{
			throw new Exception(sprintf('Color does not exist: "%s"', $key));
		} else
		{
			$color = $this->colors[$key];
		}

		if (!is_null($image))
		{
			if (is_null($color['resource']))
			{
				$color['resource'] = self::htmlColorToColor($color['html'], $image);
			}

			return $color['resource'];
		} else
		{
			return $color['html'];
		}
	}

	/**
	 * \brief Set a color.
	 *
	 * \param $key The color index.
	 * \param $color The color, in HTML notation.
	 */
	public function setColor($key, $color)
	{
		$this->colors[$key] = array('html' => $color, 'resource' => null);
	}

	/**
	 * \brief Clear the generated colors.
	 *
	 * \param $image An image resource.
	 */
	public function clearColors($image)
	{
		foreach ($this->colors as $index => $color)
		{
			imagecolordeallocate($image, $color['resource']);
			$this->colors[$index]['resource'] = null;
		}
	}

	/**
	 * \brief Compute the height of the graph.
	 *
	 * \return The computed height of the graph.
	 */
	protected abstract function computeHeight();

	/**
	 * \brief Get the image MIME type.
	 *
	 * \return The image MIME type.
	 */
	public abstract function getMimeType();

	/**
	 * \brief Render the image.
	 *
	 * \param $image The image canvas.
	 * \return The rendered image data.
	 */
	protected abstract function renderImage($image);

	/**
	 * \brief Render the graph.
	 */
	public function render()
	{
		$exception = null;

		$image = $this->createImage();

		try
		{
			$this->renderImage($image);
		}
		catch (Exception $ex)
		{
			$exception = $ex;
		}

		$this->destroyImage($image);

		if (!is_null($exception))
		{
			throw $exception;
		}
	}

	/**
	 * \brief Create the image resource.
	 *
	 * \return The image resource.
	 */
	protected function createImage()
	{
		$image = imagecreatetruecolor($this->getWidth(), $this->getHeight());

		if ($image === false)
		{
			throw new Exception('Cannot create image.');
		}

		return $image;
	}

	/**
	 * \brief Destroy an image resource.
	 *
	 * \return true on success, false otherwise.
	 */
	protected function destroyImage($image)
	{
		$this->clearColors($image);

		return imagedestroy($image);
	}

	// Static functions

	/**
	 * \brief Convert HTML color to rgb color.
	 *
	 * \param $color A color in HTML notation (ie. "#ffffff" or "#fff"). Leading # may be omitted.
	 * \param $image An image resource.
	 * \return The new color.
	 */
	public static function htmlColorToColor($color, $image)
	{
		if ($color[0] == '#')
		{
			$color = substr($color, 1);
		}

		if (strlen($color) == 6)
		{
			list($r, $g, $b) = array($color[0].$color[1],
					$color[2].$color[3],
					$color[4].$color[5]);
		}	elseif (strlen($color) == 3)
		{
			list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
		}	else
		{
			throw new Exception(sprintf('Invalid HTML color: "%s"', $color));
		}

		$r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

		return imagecolorallocate($image, $r, $g, $b);
	}
}
