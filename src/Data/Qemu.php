<?php

namespace SapiStudio\Proxmox\Data;

class Qemu extends \SapiStudio\Proxmox\Request
{
    private $virtualMachineId   = null;
    private $nodeId             = null;
    
    /**
     * Qemu::setVmId()
     * 
     * @return
     */
    public function setVmId($vmid = 0){
        $this->virtualMachineId = $vmid;
        return $this;
    }
    
    /**
     * Qemu::setNodeId()
     * 
     * @return
     */
    public function setNodeId($nodeId = 0){
        $this->nodeId = $nodeId;
        return $this;
    }
    
    /**
     * Qemu::qemuVmid()
     * 
     * @return
     */
    public function qemuVmid($node, $vmid)
    {
        return self::Request("/nodes/$node/qemu/$vmid");
    }

    /**
     * Qemu::qemuStatus()
     * 
     * @return
     */
    public function qemuStatus($node, $vmid)
    {
        return self::Request("/nodes/$node/qemu/$vmid/status");
    }

    /**
     * Qemu::qemuCurrent()
     * 
     * @return
     */
    public function qemuCurrent($node, $vmid)
    {
        return self::Request("/nodes/$node/qemu/$vmid/status/current");
    }

    /**
     * Qemu::qemuRrddata()
     * 
     * @return
     */
    public function qemuRrddata($node, $vmid, $timeframe = null)
    {
        $optional['timeframe'] = !empty($timeframe) ? $timeframe : null;
        return self::Request("/nodes/$node/qemu/$vmid/rrddata", $optional);
    }

    /**
     * Qemu::qemuDelete()
     * 
     * @return
     */
    public function qemuDelete($node, $vmid)
    {
        return self::Request("/nodes/$node/qemu/$vmid", [], "DELETE");
    }

    /**
     * Qemu::qemuConfig()
     * 
     * @return
     */
    public function qemuConfig($node, $vmid)
    {
        return self::Request("/nodes/$node/qemu/$vmid/config");
    }

    /**
     * Qemu::qemuSetConfig()
     * 
     * @return
     */
    public function qemuSetConfig($node, $vmid, $data = [])
    {
        return self::Request("/nodes/$node/qemu/$vmid/config", $data, 'PUT');
    }

    /**
     * Qemu::qemuCreateConfig()
     * 
     * @return
     */
    public function qemuCreateConfig($node, $vmid, $data = [])
    {
        return self::Request("/nodes/$node/qemu/$vmid/config", $data, 'POST');
    }

    /**
     * Qemu::qemuResume()
     * 
     * @return
     */
    public function qemuResume($node, $vmid, $data = [])
    {
        $fields = null;
        return self::Request("/nodes/$node/qemu/$vmid/status/resume", $data, 'POST');
    }

    /**
     * Qemu::qemuReset()
     * 
     * @return
     */
    public function qemuReset($node, $vmid, $data = [])
    {
        $fields = null;
        return self::Request("/nodes/$node/qemu/$vmid/status/reset", $data, 'POST');
    }
    
    /**
     * Qemu::qemuShutdown()
     * 
     * @return
     */
    public function qemuShutdown($node, $vmid, $data = [])
    {
        $fields = null;
        return self::Request("/nodes/$node/qemu/$vmid/status/shutdown", $data, 'POST');
    }

    /**
     * Qemu::qemuStart()
     * 
     * @return
     */
    public function qemuStart($node, $vmid, $data = [])
    {
        $fields = null;
        return self::Request("/nodes/$node/qemu/$vmid/status/start", $data, 'POST');
    }
    
    /**
     * Qemu::qemuStop()
     * 
     * @return
     */
    public function qemuStop($node, $vmid, $data = [])
    {
        $fields = null;
        return self::Request("/nodes/$node/qemu/$vmid/status/stop", $data, 'POST');
    }

    /**
     * Qemu::qemuSuspend()
     * 
     * @return
     */
    public function qemuSuspend($node, $vmid, $data = [])
    {
        $fields = null;
        return self::Request("/nodes/$node/qemu/$vmid/status/suspend", $data, 'POST');
    }

    /**
     * Qemu::qemuClone()
     * 
     * @return
     */
    public function qemuClone($node, $vmid, $data = [])
    {
        $fields = ['newid', 'name', 'target', 'full' => 1];
        return self::Request("/nodes/$node/qemu/$vmid/clone", $data, 'POST');
    }

    /**
     * Qemu::qemuCreate()
     * 
     * @return
     */
    public function qemuCreate($node, $data = [])
    {
        $fields = ['vmid', 'name', 'ostype' => 'win10', 'ide2' => 'local:iso/win_10_64bit_trial_90days.iso,media=cdrom', 'ide0' => 'local-lvm:32', 'sockets' => 2, 'cores', 'numa' => 0, 'memory', 'net0' => 'e1000,bridge=vmbr0,tag=70', 'scsihw' => 'virtio-scsi-pci'];
        return self::Request("/nodes/$node/qemu", $data, "POST");
    }
}