<?php

namespace App\Service;


use App\Common\Enum\HttpCode;
use App\Model\Inscertificate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class InscertificateService extends BaseService
{
    /**
     * 添加司法鉴定机构证书
     * @param $data
     * @return array|bool
     */
    public function addInscertificate(array $data) : bool
    {
        $has = Inscertificate::where('zsbh', $data['zsbh'])->count();
        if ($has > 0) {
            $this->error = '该司法鉴定机构证书已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        $data['jdjg_ywfw'] = implode(',', $data['jdjg_ywfw']);
        $data['fzrq'] = strtotime($data['fzrq']);
        $data['zgsh_yxqz'] = strtotime($data['zgsh_yxqz']);
        DB::beginTransaction();
        $inscertificate = Inscertificate::create($data);
        if (!$inscertificate) {
            $this->error = '添加失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }

    /**
     * 编辑司法鉴定机构代码
     * @param $data
     * @return bool
     */
    public function editInscertificate(array $data) : bool
    {
        $inscertificate = Inscertificate::find($data['id']);
        // 手动开启事务
        DB::beginTransaction();

        $inscertificate->jdjg_dm        = $data['jdjg_dm'];
        $inscertificate->zsbh           = $data['zsbh'];
        $inscertificate->jdjg_fzr       = $data['jdjg_fzr'];
        $inscertificate->jdjg_ywfw      = implode(',', $data['jdjg_ywfw']);
        $inscertificate->fzrq           = strtotime($data['fzrq']);
        $inscertificate->zgsh_yxqz      = strtotime($data['zgsh_yxqz']);
        $inscertificate->if_jh          = $data['if_jh'];

        $re = $inscertificate->save();
        if ($re === false) {
            $this->error = '修改失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }
    /**
     * 删除司法鉴定机构代码
     * @param $data
     * @return bool
     */
    public function delInscertificate(int $inscertificateId) : bool
    {
        $inscertificate = Inscertificate::find($inscertificateId);
        if (!$inscertificate) {
            $this->error = '该司法鉴定机构证书不存在';
            $this->httpCode = HttpCode::GONE;
            return false;
        }
        DB::beginTransaction();
        $re = $inscertificate->delete();
        if ($re === false) {
            $this->error = '删除失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }
}