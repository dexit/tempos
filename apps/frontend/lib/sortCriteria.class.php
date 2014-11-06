<?php

class SortCriteria
{

	static public function addSortCriteria($criteria, $sort_alias, $aliases, $sort_direction)
	{
		if (!isset($aliases[$sort_alias]))
		{
			return;
		}

		$sort_column = $aliases[$sort_alias];

		if ($sort_direction == 'up')
		{
			if (is_array($sort_column))
			{
				foreach ($sort_column as $column)
				{
					$criteria->addAscendingOrderByColumn($column);
				}
			} else
			{
				$criteria->addAscendingOrderByColumn($sort_column);
			}
		} else
		{
			if (is_array($sort_column))
			{
				foreach ($sort_column as $column)
				{
					$criteria->addDescendingOrderByColumn($column);
				}
			} else
			{
				$criteria->addDescendingOrderByColumn($sort_column);
			}
		}
	}
}
