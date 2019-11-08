<?php

namespace SapiStudio\Proxmox\Data;

class Qemu extends \SapiStudio\Proxmox\Request
{
    private $virtualMachineId   = null;
    private $nodeId             = null;
    
    /** Qemu::__construct()*/
    public function __construct($nodeId = null,$vmid = null)
    {
        if(!$nodeId || !$vmid){
            throw new \Exception('Invalid Qemu constructor');
        }
        $this->virtualMachineId = $vmid;
        $this->nodeId           = $nodeId;
    }
    
    /** Qemu::getId()*/
    public function getId()
    {
        return $this->virtualMachineId;
    }
    
    /** Qemu::qemuVmid()*/
    public function qemuVmid()
    {
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId");
    }

    /** Qemu::qemuStatus()*/
    public function qemuStatus()
    {
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId/status");
    }

    /** Qemu::qemuCurrent()*/
    public function qemuCurrent()
    {
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId/status/current");
    }

    /** Qemu::qemuRrddata()*/
    public function qemuRrddata($timeframe = null)
    {
        $optional['timeframe'] = !empty($timeframe) ? $timeframe : null;
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId/rrddata", $optional);
    }

    /** Qemu::qemuDelete()*/
    public function qemuDelete()
    {
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId", [], "DELETE");
    }

    /** Qemu::qemuConfig()*/
    public function qemuConfig()
    {
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId/config");
    }

    /** Qemu::qemuSetConfig()*/
    public function qemuSetConfig($data = [])
    {
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId/config", $data, 'PUT');
    }

    /** Qemu::qemuCreateConfig()*/
    public function qemuCreateConfig($data = [])
    {
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId/config", $data, 'POST');
    }

    /** Qemu::qemuResume()*/
    public function qemuResume($data = [])
    {
        $fields = null;
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId/status/resume", $data, 'POST');
    }

    /** Qemu::qemuReset()*/
    public function qemuReset($data = [])
    {
        $fields = null;
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId/status/reset", $data, 'POST');
    }
    
    /** Qemu::qemuShutdown()*/
    public function qemuShutdown($data = [])
    {
        $fields = null;
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId/status/shutdown", $data, 'POST');
    }

    /** Qemu::qemuStart()*/
    public function qemuStart($data = [])
    {
        $fields = null;
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId/status/start", $data, 'POST');
    }
    
    /** Qemu::qemuStop()*/
    public function qemuStop($data = [])
    {
        $fields = null;
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId/status/stop", $data, 'POST');
    }

    /** Qemu::qemuSuspend()*/
    public function qemuSuspend($data = [])
    {
        $fields = null;
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId/status/suspend", $data, 'POST');
    }

    /** Qemu::qemuClone()*/
    public function qemuClone($data = [])
    {
        $fields = ['newid', 'name'];
        if(!isset($data['target']))
            $data['target'] = $this->nodeId;
        $data['full']   = 1;
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId/clone", $data, 'POST');
    }
}
