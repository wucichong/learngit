<?php
class ComplainAction extends BaseAction
{
    //申诉webview界面
    public function listview()
    {
        $mode = getint('mode');//查看方式，1.自查，2待审核
        $showlist = getint('showlist');
        $e = getint('e');
        $token = get('token');
        $company = D('Company')->find($this->U_CID);
        $api = $company['c_api'];

        $mode = $mode ? $mode : 1;

        //如果是首页模式
        if (!$showlist) {
            //判断是否是管理员
            $isAdmin = D('User')->where('u_boss='.$this->U_ID.' OR u_cfo LIKE "%'.$this->U_ID.'%"')->select(); 
            if ($isAdmin) {
                
                $this->assign('isAdmin',1);

                //检查是否有待审批的记录
                $watingList = D('Complain')->where('cp_jid='.$this->U_ID.' && cp_result = 1')->count();
                if ($watingList) {
                    $this->assign('num',$watingList);
                    $this->display('complain_indexview');
                    die;
                }   
            }else{
                $this->assign('isAdmin',0);
            }
        }

        $this->assign('mode',$mode);
        $this->assign('e',$e);
        $this->assign('token',$token);
        $this->assign('api',$api);

        $listdata = D('Complain')->getListData($mode,$this->U_ID);

        $this->assign('listdata',$listdata);

        if ($mode == 1) {
            $this->display('complain_self_list');
        }else{
            $this->display('complain_judge_list');
        }
    }

    //列表webview界面
    public function getlistdata()
    {
        $mode = getint('mode');//查看方式，1.自查，2待审核
        $e = getint('e');
        $token = get('token');
        $company = D('Company')->find($this->U_CID);
        $api = $company['c_api'];
        $page = getint('page');

        $listdata = D('Complain')->getListData($mode,$this->U_ID,$page);

        $this->R($listdata);
    }

}
