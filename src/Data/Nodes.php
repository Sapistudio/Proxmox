<?php

namespace SapiStudio\Proxmox\Data;

class Nodes extends \SapiStudio\Proxmox\Request
{
  /**
    * Cluster node index.
    * GET /api2/json/nodes
  */
  public function listNodes()
  {
      return self::Request("/nodes");
  }
  
  /**
    * Virtual machine index (per node).
    * GET /api2/json/nodes/{node}/qemu
    * @param string   $node     The cluster node name.
  */
  public function Qemu($node)
  {
      return self::Request("/nodes/$node/qemu");
  }
  
  /**
    * Directory index
    * GET /api2/json/nodes/{node}/qemu/{vmid}
    * @param string   $node     The cluster node name.
    * @param integer  $vmid     The (unique) ID of the VM.
  */
  public function QemuVmid($node,$vmid)
  {
      return self::Request("/nodes/$node/qemu/$vmid");
  }
  
  /**
    * Directory index
    * GET /api2/json/nodes/{node}/qemu/{vmid}/status
    * @param string   $node    The cluster node name.
    * @param integer  $vmid    The (unique) ID of the VM.
  */
  public function qemuStatus($node, $vmid)
  {
      return self::Request("/nodes/$node/qemu/$vmid/status");
  }
  
  /**
    * Get virtual machine status.
    * GET /api2/json/nodes/{node}/qemu/{vmid}/status/current
    * @param string   $node    The cluster node name.
    * @param integer  $vmid    The (unique) ID of the VM.
  */
  public function qemuCurrent($node, $vmid)
  {
      return self::Request("/nodes/$node/qemu/$vmid/status/current");
  }
  
  /**
    * Get current virtual machine configuration. This does not include pending configuration changes (see 'pending' API).
    * GET /api2/json/nodes/{node}/qemu/{vmid}/config
    * @param string   $node    The cluster node name.
    * @param integer  $vmid    The (unique) ID of the VM.
  */
  public function qemuConfig($node, $vmid)
  {
      return self::Request("/nodes/$node/qemu/$vmid/config");
  }
  
  /**
    * Read VM RRD statistics
    * GET /api2/json/nodes/{node}/qemu/{vmid}/rrddata
    * @param string   $node    The cluster node name.
    * @param integer  $vmid    The (unique) ID of the VM.
    * @param enum     $timeframe   Specify the time frame you are interested in.
  */
  public function qemuRrddata($node, $vmid, $timeframe = null)
  {
      $optional['timeframe'] = !empty($timeframe) ? $timeframe : null;
      return self::Request("/nodes/$node/qemu/$vmid/rrddata", $optional);
  }
  
  /**
    * Destroy the vm (also delete all used/owned volumes)
    * DELETE /api2/json/nodes/{node}/qemu/{vmid}
    * @param string   $node    The cluster node name.
    * @param integer  $vmid    The (unique) ID of the VM.
  */
  public function deleteQemu($node, $vmid)
  {
      return self::Request("/nodes/$node/qemu/$vmid", null,"DELETE");
  }
  
  /**
    * Set virtual machine options (synchrounous API) - You should consider using the POST method instead for any actions involving hotplug or storage allocation.
    * PUT /api2/json/nodes/{node}/qemu/{vmid}/config
    * @param string   $node    The cluster node name.
    * @param integer  $vmid    The (unique) ID of the VM.
    * @param array    $data
  */
  public function setQemuConfig($node, $vmid, $data = [])
  {
      return self::Request("/nodes/$node/qemu/$vmid/config", $data, 'PUT');
  }
  
  
  /**
    * Set virtual machine options (asynchrounous API).
    * POST /api2/json/nodes/{node}/qemu/{vmid}/config
    * @param string   $node    The cluster node name.
    * @param integer  $vmid    The (unique) ID of the VM.
    * @param array    $data
  */
  public function createQemuConfig($node, $vmid, $data = [])
  {
      return self::Request("/nodes/$node/qemu/$vmid/config", $data, 'POST');
  }
  
  /**
    * Resume the virtual machine.
    * POST /api2/json/nodes/{node}/qemu/{vmid}/status/resume
    * @param string   $node    The cluster node name.
    * @param integer  $vmid    The (unique) ID of the VM.
    * @param array    $data
  */
  public function qemuResume($node, $vmid, $data = [])
  {
    $fields = null;
      return self::Request("/nodes/$node/qemu/$vmid/status/resume", $data, 'POST');
  }
  
  /**
    * Reset the virtual machine.
    * POST /api2/json/nodes/{node}/qemu/{vmid}/status/reset
    * @param string   $node    The cluster node name.
    * @param integer  $vmid    The (unique) ID of the VM.
    * @param array    $data
  */
  public function qemuReset($node, $vmid, $data = [])
  {
    $fields = null;
      return self::Request("/nodes/$node/qemu/$vmid/status/reset", $data, 'POST');
  }
  /**
    * Shutdown virtual machine. This is similar to pressing the power button on a physical machine.This will send an ACPI event for the guest OS, which should then proceed to a clean shutdown.
    * POST /api2/json/nodes/{node}/qemu/{vmid}/status/shutdown
    * @param string   $node    The cluster node name.
    * @param integer  $vmid    The (unique) ID of the VM.
    * @param array    $data
  */
  public function qemuShutdown($node, $vmid, $data = [])
  {
    $fields = null;
      return self::Request("/nodes/$node/qemu/$vmid/status/shutdown", $data, 'POST');
  }
  
  /**
    * Start the virtual machine.
    * POST /api2/json/nodes/{node}/qemu/{vmid}/status/start
    * @param string   $node    The cluster node name.
    * @param integer  $vmid    The (unique) ID of the VM.
    * @param array    $data
  */
  public function qemuStart($node, $vmid, $data = [])
  {
    $fields = null;
      return self::Request("/nodes/$node/qemu/$vmid/status/start", $data, 'POST');
  }
  /**
    * Stop virtual machine. The qemu process will exit immediately. Thisis akin to pulling the power plug of a running computer and may damage the VM data
    * POST /api2/json/nodes/{node}/qemu/{vmid}/status/stop
    * @param string   $node    The cluster node name.
    * @param integer  $vmid    The (unique) ID of the VM.
    * @param array    $data
  */
  public function qemuStop($node, $vmid, $data = [])
  {
    $fields = null;
      return self::Request("/nodes/$node/qemu/$vmid/status/stop", $data, 'POST');
  }
  
  /**
    * Suspend the  virtual machine.
    * POST /api2/json/nodes/{node}/qemu/{vmid}/status/suspend
    * @param string   $node    The cluster node name.
    * @param integer  $vmid    The (unique) ID of the VM.
    * @param array    $data
  */
  public function qemuSuspend($node, $vmid, $data = [])
  {
    $fields = null;
      return self::Request("/nodes/$node/qemu/$vmid/status/suspend", $data, 'POST');
  }
  
  
  /**
    * Create a copy of virtual machine/template
    * POST /api2/json/nodes/{node}/qemu/{vmid}/clone
    * @param string   $node    The cluster node name.
    * @param integer  $vmid    The (unique) ID of the VM.
    * @param array    $data
  */
  public function qemuClone($node, $vmid, $data = [])
  {
    $fields =['newid','name','target','full'=>1];
      return self::Request("/nodes/$node/qemu/$vmid/clone", $data, 'POST');
  }
  
  /**
    * Create or restore a virtual machine.
    * POST /api2/json/nodes/{node}/qemu
    * @param string   $node     The cluster node name.
    * @param array    $data
  */
  public function createQemu($node, $data = [])
  {
    $fields = ['vmid','name','ostype'=>'win10','ide2'=>'local:iso/win_10_64bit_trial_90days.iso,media=cdrom','ide0'=>'local-lvm:32','sockets'=>2,'cores','numa'=>0,'memory','net0'=>'e1000,bridge=vmbr0,tag=70','scsihw'=>'virtio-scsi-pci'];

      return self::Request("/nodes/$node/qemu", $data, "POST");
  }
  
  
  /**
    * Read task list for one node (finished tasks).
    * GET /api2/json/nodes/{node}/tasks
    * @param string   $node     The cluster node name.
    * @param boolean  $errors
    * @param integer  $limit
    * @param integer  $vmid     Only list tasks for this VM.
    * @param integer  $start
  */
  public function Tasks($node, $errors = null, $limit = null, $vmid = null, $start = null)
  {
      $optional['errors']  = !empty($errors) ? $errors : false;
      $optional['limit']   = !empty($limit) ? $limit : null;
      $optional['vmid']    = !empty($vmid) ? $vmid : null;
      $optional['start']   = !empty($start) ? $start : null;
      return self::Request("/nodes/$node/tasks", $optional);
  }
  /**
    * Read task upid
    * GET /api2/json/nodes/{node}/tasks/{upid}
    * @param string   $node     The cluster node name.
    * @param string   $upid
  */
  public function tasksUpid($node, $upid)
  {
      return self::Request("/nodes/$node/tasks/$upid");
  }
  /**
    * Stop a task.
    * DELETE /api2/json/nodes/{node}/tasks/{upid}
    * @param string   $node     The cluster node name.
    * @param string   $upid
  */
  public function tasksStop($node, $upid)
  {
      return self::Request("/nodes/$node/tasks/$upid", null, "DELETE");
  }
  /**
    * Read task log.
    * GET /api2/json/nodes/{node}/tasks/{upid}/log
    * @param string   $node     The cluster node name.
    * @param string   $upid
    * @param integer  $limit
    * @param integer  $start
  */
  public function tasksLog($node, $upid, $limit = null, $start = null)
  {
      $optional['limit']   = !empty($limit) ? $limit : null;
      $optional['start']   = !empty($start) ? $start : null;
      return self::Request("/nodes/$node/tasks/$upid/log", $optional);
  }
  /**
    * Read task status.
    * GET /api2/json/nodes/{node}/tasks/{upid}/status
    * @param string   $node     The cluster node name.
    * @param string   $upid
  */
  public function tasksStatus($node, $upid)
  {
      return self::Request("/nodes/$node/tasks/$upid/status");
  }
}