<?php

namespace SapiStudio\Proxmox\Data;
use SapiStudio\Proxmox\Handler;

class Nodes extends \SapiStudio\Proxmox\Request
{
    private $nodeId             = null;
    
    /** Qemu::__construct()*/
    public function __construct($nodeId = null)
    {
        if(!$nodeId){
            throw new \Exception('Invalid Node constructor');
        }
        $this->nodeId           = $nodeId;
    }
    
    /** Nodes::listNodeQemus()*/
    public function listNodeQemus($applyModification = null)
    {
        $qemus = self::Request("/nodes/$this->nodeId/qemu");
        if(!$qemus || !isset($qemus->data))
            return false;
        foreach($qemus->data as $index=>$vmData){
            $object = $this->getQemu($vmData->vmid);
            if($applyModification)
                $object->$applyModification();
            $return[$index] = $object;
        }
        return $return;
    }
    
    /** Nodes::getQemu()*/
    public function getQemu($qemuId = null)
    {
        return new Qemu($this->nodeId,$qemuId);
    }

    /** Nodes::qemuCreate()*/
    public function qemuCreate($data = [])
    {
        $fields = ['vmid', 'name', 'ostype' => 'win10', 'ide2' => 'local:iso/win_10_64bit_trial_90days.iso,media=cdrom', 'ide0' => 'local-lvm:32', 'sockets' => 2, 'cores', 'numa' => 0, 'memory', 'net0' => 'e1000,bridge=vmbr0,tag=70', 'scsihw' => 'virtio-scsi-pci'];
        return self::Request("/nodes/$this->nodeId/qemu", $data, "POST");
    }

    /** Nodes::Tasks()*/
    public function Tasks($errors = null, $limit = null, $vmid = null, $start = null)
    {
        $optional['errors'] = !empty($errors) ? $errors : false;
        $optional['limit'] = !empty($limit) ? $limit : null;
        $optional['vmid'] = !empty($vmid) ? $vmid : null;
        $optional['start'] = !empty($start) ? $start : null;
        return self::Request("/nodes/$this->nodeId/tasks", $optional);
    }

    /** Nodes::tasksUpid()*/
    public function tasksUpid($upid)
    {
        return self::Request("/nodes/$this->nodeId/tasks/$upid");
    }

    /** Nodes::tasksStop()*/
    public function tasksStop($upid)
    {
        return self::Request("/nodes/$this->nodeId/tasks/$upid", null, "DELETE");
    }

    /** Nodes::tasksLog()*/
    public function tasksLog($upid, $limit = null, $start = null)
    {
        $optional['limit'] = !empty($limit) ? $limit : null;
        $optional['start'] = !empty($start) ? $start : null;
        return self::Request("/nodes/$this->nodeId/tasks/$upid/log", $optional);
    }

    /** Nodes::tasksStatus()*/
    public function tasksStatus($upid)
    {
        return self::Request("/nodes/$this->nodeId/tasks/$upid/status");
    }
}
