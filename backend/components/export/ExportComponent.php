<?php 

namespace backend\components\export;

use Yii;
use yii\base\Component;
use \PHPExcel;
use \PHPExcel_IOFactory;
use \PHPExcel_Settings;
use \PHPExcel_Style_Fill;
use \PHPExcel_Writer_Abstract;
use \PHPExcel_Writer_CSV;
use \PHPExcel_Worksheet;

use yii\helpers\ArrayHelper;

/**
*  
*/
class ExportComponent extends Component
{

    /**
     * Export formats
     */
    const FORMAT_CSV = 'CSV';
    const FORMAT_EXCEL = 'Excel5';
    const FORMAT_EXCEL_X = 'Excel2007';
  

    /**
     * @var PHPExcel object instance
     */
    protected $_objPHPExcel;

    /**
     * @var PHPExcel_Writer_Abstract object instance
     */
    protected $_objPHPExcelWriter;

    /**
     * @var PHPExcel_Worksheet object instance
     */
    protected $_objPHPExcelSheet;

    /**
     * @var string the exported output file name. Defaults to 'grid-export';
     */
    public $filename='Export';

    /**
     * @var bool whether to clear all previous / parent buffers. Defaults to `false`.
     */
    public $clearBuffers = false;

    /**
     * @var boolean whether to use font awesome icons for rendering the icons as defined in `exportConfig`. If set to
     *     `true`, you must load the FontAwesome CSS separately in your application.
     */
    public $fontAwesome = false;

    /**
     * @var string encoding for the downloaded file header. Defaults to 'utf-8'.
     */
    public $encoding = 'utf-8';

        /**
     * @var array the default export configuration
     */

    protected $_defaultExportConfig = [];

    
    /**
     * @var int the table beginning row
     */
    protected $_beginRow = 1;

    /**
     * @var int the current table end row
     */
    protected $_endRow = 1;

    /**
     * @var int the current table end column
     */
    protected $_endCol = 1;


    
    /**
     * @var string the data output format type. Defaults to `ExportMenu::FORMAT_EXCEL_X`.
     */
    protected $_exportType = self::FORMAT_EXCEL_X;


    /**
     * @var array the export configuration. The array keys must be the one of the `format` constants (CSV, HTML, TEXT,
     *     EXCEL, PDF) and the array value is a configuration array consisting of these settings:
     * - label: string, the label for the export format menu item displayed
     * - icon: string, the glyphicon or font-awesome name suffix to be displayed before the export menu item label. If
     *     set to an empty string, this will not be displayed.
     * - iconOptions: array, HTML attributes for export menu icon.
     * - linkOptions: array, HTML attributes for each export item link.
     * - filename: the base file name for the generated file. Defaults to 'grid-export'. This will be used to generate
     *     a default file name for downloading.
     * - extension: the extension for the file name
     * - alertMsg: string, the message prompt to show before saving. If this is empty or not set it will not be
     *     displayed.
     * - mime: string, the mime type (for the file format) to be set before downloading.
     * - writer: string, the PHP Excel writer type
     * - options: array, HTML attributes for the export menu item.
     */
    public $exportConfig = [];

    /**
     * Sets the default export configuration
     *
     * @return void
     */
    protected function setDefaultExportConfig()
    {
        $isFa = $this->fontAwesome;
        $this->_defaultExportConfig = [
            self::FORMAT_CSV => [
                'label' => Yii::t('app', 'CSV'),
                'icon' => $isFa ? 'file-code-o' : 'floppy-open',
                'iconOptions' => ['class' => 'text-primary'],
                'linkOptions' => [],
                'options' => ['title' => Yii::t('app', 'Comma Separated Values')],
                'alertMsg' => Yii::t('app', 'The CSV export file will be generated for download.'),
                'mime' => 'application/csv',
                'extension' => 'csv',
                'writer' => 'CSV',
            ],
            self::FORMAT_EXCEL => [
                'label' => Yii::t('app', 'Excel 95 +'),
                'icon' => $isFa ? 'file-excel-o' : 'floppy-remove',
                'iconOptions' => ['class' => 'text-success'],
                'linkOptions' => [],
                'options' => ['title' => Yii::t('app', 'Microsoft Excel 95+ (xls)')],
                'alertMsg' => Yii::t('app', 'The EXCEL 95+ (xls) export file will be generated for download.'),
                'mime' => 'application/vnd.ms-excel',
                'extension' => 'xls',
                'writer' => 'Excel5',
            ],
            self::FORMAT_EXCEL_X => [
                'label' => Yii::t('app', 'Excel 2007+'),
                'icon' => $isFa ? 'file-excel-o' : 'floppy-remove',
                'iconOptions' => ['class' => 'text-success'],
                'linkOptions' => [],
                'options' => ['title' => Yii::t('app', 'Microsoft Excel 2007+ (xlsx)')],
                'alertMsg' => Yii::t('app', 'The EXCEL 2007+ (xlsx) export file will be generated for download.'),
                'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'extension' => 'xlsx',
                'writer' => 'Excel2007',
            ],
        ];
    }

    /**
     * Initializes PHP Excel Object Instance
     *
     * @return void
     */
    public function initPHPExcel()
    {
        $this->_objPHPExcel = new PHPExcel();
        $creator = 'Tracker';
        $title = 'Tracker';
        $subject = '';
        $description = Yii::t('app', 'Tracker Report');
        $category = '';
        $keywords = '';
        $manager = '';
        $company = 'Tracker';
        $created = date("Y-m-d H:i:s");
        $lastModifiedBy = 'Tracker';
      
        $this->_objPHPExcel->getProperties()
            ->setCreator($creator)
            ->setTitle($title)
            ->setSubject($subject)
            ->setDescription($description)
            ->setCategory($category)
            ->setKeywords($keywords)
            ->setManager($manager)
            ->setCompany($company)
            ->setCreated($created)
            ->setLastModifiedBy($lastModifiedBy);
        
    }
	
      /**
     * Initializes export settings
     *
     * @return void
     */
    public function initExport()
    {
        $this->setDefaultExportConfig();
        $this->exportConfig = ArrayHelper::merge($this->_defaultExportConfig, $this->exportConfig);
        if (empty($this->filename)) {
            $this->filename = Yii::t('app', 'export');
        }
    }

      /**
     * Initializes PHP Excel Writer Object Instance
     *
     * @param string $type the writer type as set in export config
     *  $type: Excel2007  |  Excel5  | CSV
     * @return void
     */
    public function initPHPExcelWriter($type)
    {
        /**
         * @var PHPExcel_Writer_CSV $writer
         */
        $this->_objPHPExcelWriter = PHPExcel_IOFactory::createWriter($this->_objPHPExcel, $type);
       
    }

    /**
     * Initializes PHP Excel Worksheet Instance
     *
     * @return void
     */
    public function initPHPExcelSheet()
    {
        $this->_objPHPExcel->setActiveSheetIndex(0);  
        $this->_objPHPExcelSheet = $this->_objPHPExcel->getActiveSheet();
        $this->_objPHPExcelSheet->setTitle('Sheet'); 
    }

    /**
     * Destroys PHP Excel Object Instance
     *
     * @return void
     */
    public function destroyPHPExcel()
    {
        if (isset($this->_objPHPExcel)) {
            $this->_objPHPExcel->disconnectWorksheets();
        }
        unset($this->_objPHPExcelWriter, $this->_objPHPExcelSheet, $this->_objPHPExcel);
    }

    /**
     * Clear output buffers
     *
     * @return void
     */
    protected function clearOutputBuffers()
    {
        if ($this->clearBuffers) {
            while (ob_get_level() > 0) {
                ob_end_clean();
            }
        } else {
            ob_end_clean();
        }
    }

        /**
     * Set HTTP headers for download
     *
     * @return void
     */
    protected function setHttpHeaders()
    {
        $config = ArrayHelper::getValue($this->exportConfig, $this->_exportType, []);
        $extension = ArrayHelper::getValue($config, 'extension', 'xlsx');
        $mime = ArrayHelper::getValue($config, 'mime', '');
        if (strstr($_SERVER["HTTP_USER_AGENT"], "MSIE") == false) {
            header("Cache-Control: no-cache");
            header("Pragma: no-cache");
        } else {
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Pragma: public");
        }
        header("Expires: Sat, 26 Jul 1979 05:00:00 GMT");
        header("Content-Encoding: {$this->encoding}");
        if (!empty($mime)) {
            header("Content-Type: {$mime}; charset={$this->encoding}");
        }
        header("Content-Disposition: attachment; filename=\"{$this->filename}.{$extension}\"");
        header("Cache-Control: max-age=0");

    }

    /**
     * Returns an excel column name.
     *
     * @param int $index the column index number
     *
     * @return string
     */
    public static function columnName($index)
    {
        $i = intval($index) - 1;
        if ($i >= 0 && $i < 26) {
            return chr(ord('A') + $i);
        }
        if ($i > 25) {
            return (self::columnName($i / 26)) . (self::columnName($i % 26 + 1));
        }
        return 'A';
    }

   public function exportMain()
   {
   }

   public function exportExcel()
   {
       $this->initExport();
       $config = ArrayHelper::getValue($this->exportConfig, $this->_exportType, []);
       $this->initPHPExcel();
       $this->initPHPExcelWriter($config['writer']);
       $this->initPHPExcelSheet();
       $this->exportMain();
       $this->clearOutputBuffers();
       $this->setHttpHeaders();
       $this->_objPHPExcelWriter->save('php://output');
       $this->destroyPHPExcel();
   }
   
   public function setFillColor($cell,$color)
   {
      $this->_objPHPExcelSheet->getStyle($cell)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
      $this->_objPHPExcelSheet->getStyle($cell)->getFill()->getStartColor()->setARGB($color);
   }


}