<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\EquipmentAsset;

class AssetClaim extends Model
{
    protected $guarded = [];
    /**
     * 获取我申领的资产的分页数据
     * @return array
     */
    public function getMyAssetClaim(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['cond'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        if ($where) $where = [['equipment_assets.zcmc', 'like', $where.'%']];
        $offset = ($page - 1) * $limit;
        $myAssetClaims = $this ->where($where)
                                 ->where('asset_claims.ly_uid',$param['my_id'])
                                 ->leftJoin('equipment_assets', 'asset_claims.ly_zcid', '=', 'equipment_assets.id')
                                 ->select('equipment_assets.*', 'asset_claims.ly_uid','asset_claims.ly_nums', 'asset_claims.ly_gsml','asset_claims.ly_zcyt','asset_claims.lyrq','asset_claims.created_at','asset_claims.if_check','asset_claims.word_path')
			                     ->offset($offset)
			                     ->limit($limit)
			                     ->orderBy($sortfield, $order)
			                     ->get();
        // 归属门类
        $gsml_arr = gsml();                       
        foreach ($myAssetClaims as $k => $v) {
            $v->ly_gsml = $gsml_arr[$v->ly_gsml];
        }
        $myAssetClaims= $myAssetClaims->toArray();
        $count = $this->where($where)
                     ->where('asset_claims.ly_uid',$param['my_id'])
                     ->leftJoin('equipment_assets', 'asset_claims.ly_zcid', '=', 'equipment_assets.id')->count();
        return [
            'count' => $count,
            'data' => $myAssetClaims
        ];
    }
    /**
     * 获取申领资产管理的分页数据
     * @return array
     */
    public function getAllAssetClaim(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['cond'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        if ($where) $where = [['equipment_assets.zcmc', 'like', $where.'%']];
        $offset = ($page - 1) * $limit;
        $allAssetClaims = EquipmentAsset::where($where)
                                 ->where('asset_claims.ly_dwdm',$param['my_dwdm'])
                                 ->where('asset_claims.ly_bmdm',$param['my_bmdm'])
                                 ->leftJoin('asset_claims', 'asset_claims.ly_zcid', '=', 'equipment_assets.id')
                                 ->leftJoin('admins', 'asset_claims.ly_uid', '=', 'admins.id')
                                 ->select('equipment_assets.*', 'asset_claims.ly_uid','asset_claims.ly_zcid','asset_claims.ly_nums', 'asset_claims.ly_gsml','asset_claims.ly_zcyt','asset_claims.lyrq','asset_claims.created_at','asset_claims.if_check','admins.real_name')
			                     ->offset($offset)
			                     ->limit($limit)
			                     ->orderBy($sortfield, $order)
			                     ->get();
        // 归属门类
        $gsml_arr = gsml();                       
        foreach ($allAssetClaims as $k => $v) {
            $v->ly_gsml = $gsml_arr[$v->ly_gsml];
        }
        $allAssetClaims= $allAssetClaims->toArray();
        $count = $this->where($where)
                     ->where('asset_claims.ly_dwdm',$param['my_dwdm'])
                     ->where('asset_claims.ly_bmdm',$param['my_bmdm'])->leftJoin('equipment_assets', 'asset_claims.ly_zcid', '=', 'equipment_assets.id')->count();
        return [
            'count' => $count,
            'data' => $allAssetClaims
        ];
    }

    /**
     * 下载资产申领表
     * @return array
     */
    public function downloadMyAssetClaim(array $data) : array
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
        $table->addCell(3000)->addText('申领日期');
        $table->addCell(4000)->addText($data['created_at']);
        $table->addRow(400);//行高400
        $table->addCell(3000)->addText('归属门类');
        $table->addCell(4000)->addText($data['ly_gsml']);
        $table->addRow(400);//行高400
        $table->addCell(3000)->addText('申领人');
        $table->addCell(4000)->addText($data['my_name']);
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
        $table->addCell(3000)->addText('申领数量');
        $table->addCell(4000)->addText($data['ly_nums']);
        $table->addRow(400);//行高400
        $table->addCell(3000)->addText('备注');
        $table->addCell(4000)->addText();
        $table->addRow(800);//行高400
        $table->addCell(3000)->addText('领导签批意见');
        $table->addCell(4000)->addText();
        $table->addRow(800);//行高400
        $table->addCell(3000)->addText('申领人签字');
        $table->addCell(4000)->addText();

        // $section->addTextBreak();//换行符
        $section->addText('第一联--固定资产管理存档');

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
        $table->addCell(3000)->addText('申领日期');
        $table->addCell(4000)->addText($data['created_at']);
        $table->addRow(400);//行高400
        $table->addCell(3000)->addText('归属门类');
        $table->addCell(4000)->addText($data['ly_gsml']);
        $table->addRow(400);//行高400
        $table->addCell(3000)->addText('申领人');
        $table->addCell(4000)->addText($data['my_name']);
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
        $table->addCell(3000)->addText('申领数量');
        $table->addCell(4000)->addText($data['ly_nums']);
        $table->addRow(400);//行高400
        $table->addCell(3000)->addText('备注');
        $table->addCell(4000)->addText();
        $table->addRow(800);//行高400
        $table->addCell(3000)->addText('领导签批意见');
        $table->addCell(4000)->addText();
        $table->addRow(800);//行高400
        $table->addCell(3000)->addText('申领人签字');
        $table->addCell(4000)->addText();

        $section->addTextBreak();//换行符
        $section->addText('第二联--固定资产管理存档');
        //生成的文档为Word2007
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $path = 'phpword/资产申领表_'.$data['id'].'.docx';
        $writer->save($path);
        // 把下载地址存到数据库里
        $this->where('id', $data['id'])->update(['word_path' => $path]);

        $all_path = asset($path); 
        return [
            'all_path' => $all_path,
        ];
    }

}
