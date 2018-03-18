<?php

namespace SapiStudio\Proxmox\Data;

class Qemu extends \SapiStudio\Proxmox\Request
{
    private $virtualMachineId   = null;
    private $nodeId             = null;
    
    
    /**
     * Qemu::__construct()
     */
    public function __construct($nodeId = null,$vmid = null)
    {
        if(!$nodeId || !$vmid){
            throw new \Exception('Invalid Qemu constructor');
        }
        $this->virtualMachineId = $vmid;
        $this->nodeId           = $nodeId;
    }
    
    /**
     * Qemu::qemuVmid()
     * 
     * @return
     */
    public function qemuVmid()
    {
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId");
    }

    /**
     * Qemu::qemuStatus()
     * 
     * @return
     */
    public function qemuStatus()
    {
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId/status");
    }

    /**
     * Qemu::qemuCurrent()
     * 
     * @return
     */
    public function qemuCurrent()
    {
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId/status/current");
    }

    /**
     * Qemu::qemuRrddata()
     * 
     * @return
     */
    public function qemuRrddata($timeframe = null)
    {
        $optional['timeframe'] = !empty($timeframe) ? $timeframe : null;
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId/rrddata", $optional);
    }

    /**
     * Qemu::qemuDelete()
     * 
     * @return
     */
    public function qemuDelete()
    {
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId", [], "DELETE");
    }

    /**
     * Qemu::qemuConfig()
     * 
     * @return
     */
    public function qemuConfig()
    {
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId/config");
    }

    /**
     * Qemu::qemuSetConfig()
     * 
     * @return
     */
    public function qemuSetConfig($data = [])
    {
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId/config", $data, 'PUT');
    }

    /**
     * Qemu::qemuCreateConfig()
     * 
     * @return
     */
    public function qemuCreateConfig($data = [])
    {
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId/config", $data, 'POST');
    }

    /**
     * Qemu::qemuResume()
     * 
     * @return
     */
    public function qemuResume($data = [])
    {
        $fields = null;
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId/status/resume", $data, 'POST');
    }

    /**
     * Qemu::qemuReset()
     * 
     * @return
     */
    public function qemuReset($data = [])
    {
        $fields = null;
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId/status/reset", $data, 'POST');
    }
    
    /**
     * Qemu::qemuShutdown()
     * 
     * @return
     */
    public function qemuShutdown($data = [])
    {
        $fields = null;
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId/status/shutdown", $data, 'POST');
    }

    /**
     * Qemu::qemuStart()
     * 
     * @return
     */
    public function qemuStart($data = [])
    {
        $fields = null;
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId/status/start", $data, 'POST');
    }
    
    /**
     * Qemu::qemuStop()
     * 
     * @return
     */
    public function qemuStop($data = [])
    {
        $fields = null;
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId/status/stop", $data, 'POST');
    }

    /**
     * Qemu::qemuSuspend()
     * 
     * @return
     */
    public function qemuSuspend($data = [])
    {
        $fields = null;
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId/status/suspend", $data, 'POST');
    }

    /**
     * Qemu::qemuClone()
     * 
     * @return
     */
    public function qemuClone($data = [])
    {
        $fields = ['newid', 'name'];
        $data['target'] = $this->nodeId;
        $data['full']   = 1;
        return self::Request("/nodes/$this->nodeId/qemu/$this->virtualMachineId/clone", $data, 'POST');
    }
}