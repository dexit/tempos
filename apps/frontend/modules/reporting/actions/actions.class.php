<?php

/**
 * reporting actions.
 *
 * @package    tempos
 * @subpackage reporting
 * @author     ISLOG
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class reportingActions extends sfActions
{
    public function executeIndex(sfWebRequest $request)
    {
        $i18n = sfContext::getInstance()->getI18N();
    
        // Disable CSRF protection
        $this->form = new ReportingForm(array(), array(), false);
        $formName = $this->form->getName();

        $this->step = sfConfig::get('app_max_reservations_on_reporting');

        $this->getUser()->syncParameters($this, 'reporting', 'index', array('offset', 'limit', $formName, 'sort_column', 'sort_direction'), $request);

        if (is_null($this->sort_column))
        {
            $this->sort_column = 'date';
            $this->sort_direction = 'up';
        }

        if (is_null($this->offset))
        {
            $this->offset = 0;
        }

        if (is_null($this->limit) || ($this->limit <= 0))
        {
            $this->limit = $this->step;
        }

        $c = new Criteria();

        SortCriteria::addSortCriteria($c, $this->sort_column, ReservationPeer::getSortAliases(), $this->sort_direction);

        $this->filtered = false;

        if (!is_null($this->$formName))
        {
            $this->filtered = true;
            $this->form->bind($this->$formName, $request->getFiles($formName));

            if ($this->form->isValid())
            {
                $this->reservation_list = ReservationPeer::report(
                    $this->form->getValue('users'),
                    $this->form->getValue('usergroups'),
                    $this->form->getValue('activities'),
                    $this->form->getValue('zones'),
                    $this->form->getValue('rooms'),
                    strtotime($this->form->getValue('begin_date')),
                    strtotime($this->form->getValue('end_date')),
                    $c
                );

                $this->count = count($this->reservation_list);
                $this->entire_reservation_list = $this->reservation_list;
                $this->reservation_list = array_slice($this->reservation_list, $this->offset, $this->limit);
            } else
            {
                $this->count = 0;
                $this->reservation_list = array();
            }
        } else
        {
            $this->count = 0;
            $this->reservation_list = array();
        }

        if (($this->offset < 0) || (($this->offset >= $this->count) && ($this->count > 0)))
        {
            $this->forward404('Invalid offset/count values.');
        }

        if (!is_null($export = $request->getParameter('export')))
        {
            $this->forward404Unless(in_array($export, array('csv', 'pdf')), sprintf('Unhandled value "%s" for export', $export));

            sfConfig::set('sf_web_debug', false);

            $activity_name = ConfigurationHelper::getParameter('Rename', 'activity_label');
            if (empty($activity_name))
            {
                $activity_name = $i18n->__('Activity');
            }
            
            $free_field_1_name = ConfigurationHelper::getParameter('Rename', 'reservation_custom_field_1');	
            $free_field_2_name = ConfigurationHelper::getParameter('Rename', 'reservation_custom_field_2');
            $free_field_3_name = ConfigurationHelper::getParameter('Rename', 'reservation_custom_field_3');

            $this->all_rows = array();
            $fields = $this->form->getValue('fields');

            foreach ($this->entire_reservation_list as $reservation)
            {
                //If there's is a temporary group, we modify the name to display "Perso. (first user, ...)"
                $resa_group_name = null;
                $ug = $reservation->getUsergroup();
                if (!is_null($ug))
                {
                    $resa_group_name = $ug->getName();
                }
                
                $this->all_rows[] = array();
                end($this->all_rows);
                // Check which field we need to display.
                foreach ($fields as $field_id) {

                    // See lib\form\ReportingForm.class.php for indexes
                    switch ($field_id) {
                        case 0: $this->all_rows[key($this->all_rows)][] = array ($i18n->__('Date'), $reservation->getDate());
                                break;
                        case 1: $this->all_rows[key($this->all_rows)][] = array ($i18n->__('User'), $reservation->getUser()->getFullName());
                                break;
                        case 2: $this->all_rows[key($this->all_rows)][] = array ($activity_name, $reservation->getActivity()->getName());
                                break;
                        case 3: $this->all_rows[key($this->all_rows)][] = array ($i18n->__('Duration'), number_format($reservation->getDuration()));
                                break;
                        case 4: $this->all_rows[key($this->all_rows)][] = array ($i18n->__('Room'), $reservation->getRoomprofile()->getRoom()->getName());
                                break;
                        case 5: $this->all_rows[key($this->all_rows)][] = array ($i18n->__('Reason'), (!is_null($reservation->getReservationreason())) ? $reservation->getReservationreason()->getName() : null);
                                break;
                        case 6: $this->all_rows[key($this->all_rows)][] = array ($i18n->__('Comment'), $reservation->getComment());
                                break;
                        case 7: $this->all_rows[key($this->all_rows)][] = array ($i18n->__('Group'), $resa_group_name);
                                break;
                        case 8: $this->all_rows[key($this->all_rows)][] = array ($i18n->__('Members count'), number_format($reservation->getMembersCount()));
                                break;
                        case 9: $this->all_rows[key($this->all_rows)][] = array ($i18n->__('Guests count'), number_format($reservation->getGuestsCount()));
                                break;
                        case 10: $this->all_rows[key($this->all_rows)][] = array ($i18n->__('Status'), number_format($reservation->getStatus()));
                                break;
                        case 11: $this->all_rows[key($this->all_rows)][] = array ($i18n->__('Price'), number_format($reservation->getPrice()));
                                break;
                        case 12:
                                $valuedFeaturesArray = $reservation->getRoomprofile()->getRoom()->getValuedFeaturesArray();
                                $concat = '';
                                foreach($valuedFeaturesArray as $featureName => $values) {
                                    $concat .= $featureName.'='.$values.' | ';
                                }
                                $concat = rtrim($concat, ' | ');
                                $this->all_rows[key($this->all_rows)][] = array ($i18n->__('Features'), $concat);
                                break;
                        case 90: $this->all_rows[key($this->all_rows)][] = array ($free_field_1_name, $reservation->getCustom1());
                                break;
                        case 91: $this->all_rows[key($this->all_rows)][] = array ($free_field_2_name, $reservation->getCustom2());
                                break;
                        case 92: $this->all_rows[key($this->all_rows)][] = array ($free_field_3_name, $reservation->getCustom3());
                                break;
                    }
                }
            }
            
            if ($export == 'csv')
            {
                $this->setLayout(false);

                $this->getResponse()->clearHttpHeaders();
                $this->getResponse()->setHttpHeader('Pragma: public', true);
                $this->getResponse()->setHttpHeader('Content-disposition', sprintf('attachment; filename="%s"', 'reporting-'.date('Y-m-d').'.csv'));
                $this->getResponse()->setContentType('text/csv; charset=utf-8');
                $this->getResponse()->sendHttpHeaders();

                $this->setTemplate('exportCSV');
            }
            else if ($export == 'pdf')
            {
                $config = sfTCPDFPluginConfigHandler::loadConfig();

                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

                // set document information
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('ISLOG Tempos');
                $pdf->SetTitle('Tempos report export');
                $pdf->SetSubject('Tempos export');
                $pdf->SetKeywords('Tempos, report, ISLOG');

                $pdf->SetHeaderData(
                    sfConfig::get('app_has_logo'),
                    PDF_HEADER_LOGO_WIDTH,
                    $i18n->__('Report'),
                    sprintf($i18n->__('Usage report from %s to %s'), $this->form->getValue('begin_date'), $this->form->getValue('end_date'))
                );

                $pdf->setFooterData(array(0,64,0), array(0,64,128));

                $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

                $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

                $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

                $pdf->setFontSubsetting(true);
                $pdf->SetFont('dejavusans', '', 10, '', true);

                $pdf->AddPage();

                $this->writeHtmlTable($pdf, $this->all_rows);

                $pdf->Output('reporting-'.date('Y-m-d').'.pdf', 'I');
                exit();
            }
        }
    }

    protected function writeHtmlTable(&$pdf, $rows)
	{
		sfLoader::loadHelpers('Tempos');

        $html = '<table border="1" style="table-layout: fixed; width: 100%;">';
        
        $html .= '<tr bgcolor="#c864c8" color="#ffffff">';
        foreach ($rows[0] as $cells) {
            $html .= '<th>'.$cells[0].'</th>';
        }
        $html .= '</tr>';

		$fill = 0;
		foreach($rows as $row) {
            $html .= '<tr bgcolor="'. ($fill ? '#e0ebff' : '#ffffff') . '" color="#000000">';
            foreach($row as $cells) {
                $html .= '<td height="10px" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 10px;">'.$cells[1].'</td>';
            }
            $fill = !$fill;
            $html .= '</tr>';
		}

        $html .= '</table>';
        
        $pdf->writeHTML($html, true, false, true, false, '');
	}
}
