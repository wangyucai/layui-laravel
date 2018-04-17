<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Inventory;

class EquipmentAsset extends Model
{
    protected $guarded = [];
    /**
     * 获取装备资产分页数据
     * @return array
     */
    public function getEquipmentAsset(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['cond'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        if ($where) $where = [['equipment_assets.zcmc', 'like', '%'.$where.'%']];
        $offset = ($page - 1) * $limit;
        $equipmentAssets = $this ->where($where)
                                 ->where('equipment_assets.zc_dwdm',$param['zc_dwdm'])
                                 ->where('equipment_assets.zc_bmdm',$param['zc_bmdm'])
                                 ->leftJoin('asset_units', 'equipment_assets.zcdw', '=', 'asset_units.zcdw_code')
                                 ->select('equipment_assets.*', 'asset_units.zcdw_name')
			                     ->offset($offset)
			                     ->limit($limit)
			                     ->orderBy($sortfield, $order)
			                     ->get();
        // 资产性质
        $zcxz_arr = zcxz();                       
        foreach ($equipmentAssets as $k => $v) {
            $v->zcxz = $zcxz_arr[$v->zcxz];
            $v->zcbh = 'GZJCYJSC+'.$v->zcbh;
        }
        $equipmentAssets= $equipmentAssets->toArray();
        $count = $this->where($where)->where('equipment_assets.zc_dwdm',$param['zc_dwdm'])
                      ->where('equipment_assets.zc_bmdm',$param['zc_bmdm'])->count();
        return [
            'count' => $count,
            'data' => $equipmentAssets
        ];
    }
    /**
     * 下载资产入库表
     * @return array
     */
    public function downloadInbound(array $data) : array
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        //设置默认样式
        $phpWord->setDefaultFontName('仿宋');//字体
        $phpWord->setDefaultFontSize(12);//字号
        //添加页面
        $section = $phpWord->createSection();
        //添加目录
        $styleTOC  = ['tabLeader' => \PhpOffice\PhpWord\Style\TOC::TABLEADER_DOT];
        $styleFont = ['spaceAfter' => 60, 'name' => 'Tahoma', 'size' => 12];
        $section->addTOC($styleFont, $styleTOC);
        //资产申领表  
        $phpWord->addFontStyle('rStyle', array('bold'=>true, '黑体'=>true, 'size'=>14));  
        $phpWord->addParagraphStyle('pStyle', array('align'=>'center', 'spaceAfter'=>100));  
        $section->addText('资产申领表', 'rStyle', 'pStyle');
        //添加表格
        $styleTable = [
            'borderColor' => '006699',
            'borderSize' => 4,
            'cellMargin' => 50,
        ];
        // $styleFirstRow = ['bgColor' => '66BBFF'];//第一行样式
        $phpWord->addTableStyle('myTable', $styleTable);
        $table = $section->addTable('myTable');
        $table->addRow(400);//行高400
        $table->addCell(3000)->addText('申请入库日期');
        $table->addCell(4000)->addText($data['kc_rkrq']);
        $table->addRow(400);//行高400
        $table->addCell(3000)->addText('经手人');
        $table->addCell(4000)->addText($data['kc_uid']);
        $table->addRow(400);//行高400
        $table->addCell(3000)->addText('名称');
        $table->addCell(4000)->addText($data['zcmc']);
        $table->addRow(400);//行高400
        $table->addCell(3000)->addText('资产编号');
        $table->addCell(4000)->addText('GZJCYJSC+'.$data['zcbh']);
        $table->addRow(400);//行高400
        $table->addCell(3000)->addText('资产品牌');
        $table->addCell(4000)->addText($data['zcpp']);
        $table->addRow(400);//行高400
        $table->addCell(3000)->addText('资产型号');
        $table->addCell(4000)->addText($data['zcxh']);
        $table->addRow(400);//行高400
        $table->addCell(3000)->addText('入库数量');
        $table->addCell(4000)->addText($data['kc_nums']);
        $table->addRow(400);//行高400
        $table->addCell(3000)->addText('取入依据');
        $table->addCell(4000)->addText($data['kc_qryj']);
        $table->addRow(800);//行高400
        $table->addCell(3000)->addText('入库经手人签字');
        $table->addCell(4000)->addText();
        $table->addRow(800);//行高400
        $table->addCell(3000)->addText('资产保管员签字');
        $table->addCell(4000)->addText();

        // $section->addTextBreak();//换行符
        $section->addText('第一联--入库经手人存档');

        // $section->addPageBreak();//分页符
        $section->addTextBreak();//换行符

        //资产申领表  
        $phpWord->addFontStyle('rStyle', array('bold'=>true, '黑体'=>true, 'size'=>14));  
        $phpWord->addParagraphStyle('pStyle', array('align'=>'center', 'spaceAfter'=>100));  
        $section->addText('资产申领表', 'rStyle', 'pStyle');
        //添加表格
        $styleTable = [
            'borderColor' => '006699',
            'borderSize' => 6,
            'cellMargin' => 50,
        ];
        // $styleFirstRow = ['bgColor' => '66BBFF'];//第一行样式
       $phpWord->addTableStyle('myTable', $styleTable);
        $table = $section->addTable('myTable');
        $table->addRow(400);//行高400
        $table->addCell(3000)->addText('申请入库日期');
        $table->addCell(4000)->addText($data['kc_rkrq']);
        $table->addRow(400);//行高400
        $table->addCell(3000)->addText('经手人');
        $table->addCell(4000)->addText($data['kc_uid']);
        $table->addRow(400);//行高400
        $table->addCell(3000)->addText('名称');
        $table->addCell(4000)->addText($data['zcmc']);
        $table->addRow(400);//行高400
        $table->addCell(3000)->addText('资产编号');
        $table->addCell(4000)->addText('GZJCYJSC+'.$data['zcbh']);
        $table->addRow(400);//行高400
        $table->addCell(3000)->addText('资产品牌');
        $table->addCell(4000)->addText($data['zcpp']);
        $table->addRow(400);//行高400
        $table->addCell(3000)->addText('资产型号');
        $table->addCell(4000)->addText($data['zcxh']);
        $table->addRow(400);//行高400
        $table->addCell(3000)->addText('入库数量');
        $table->addCell(4000)->addText($data['kc_nums']);
        $table->addRow(400);//行高400
        $table->addCell(3000)->addText('取入依据');
        $table->addCell(4000)->addText($data['kc_qryj']);
        $table->addRow(800);//行高400
        $table->addCell(3000)->addText('入库经手人签字');
        $table->addCell(4000)->addText();
        $table->addRow(800);//行高400
        $table->addCell(3000)->addText('资产保管员签字');
        $table->addCell(4000)->addText();

        $section->addTextBreak();//换行符
        $section->addText('第二联--资产保管员存档');
        //生成的文档为Word2007
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $path = 'phpword/资产入库表_'.$data['id'].'.docx';
        $writer->save($path);
        // 把下载地址存到数据库里
        Inventory::where('id', $data['id'])->update(['kc_word_path' => $path]);

        $all_path = asset($path); 
        return [
            'all_path' => $all_path,
        ];
    }
}
