<?php

namespace SapiStudio\Proxmox\Data;

class Nodes extends \SapiStudio\Proxmox\Request
{
  /**
   * Nodes::listNodes()
   * 
   * @return
   */
  public function listNodes()
  {
      return self::Request("/nodes");
  }
  
  /**
   * Nodes::listNodeQemus()
   * 
   * @return
   */
  public function listNodeQemus($node)
  {
      return self::Request("/nodes/$node/qemu");
  }
  
  
    /**
     * Nodes::qemuCreate()
     * 
     * @return
     */
    public function qemuCreate($data = [])
    {
        $fields = ['vmid', 'name', 'ostype' => 'win10', 'ide2' => 'local:iso/win_10_64bit_trial_90days.iso,media=cdrom', 'ide0' => 'local-lvm:32', 'sockets' => 2, 'cores', 'numa' => 0, 'memory', 'net0' => 'e1000,bridge=vmbr0,tag=70', 'scsihw' => 'virtio-scsi-pci'];
        return self::Request("/nodes/$node/qemu", $data, "POST");
    }
    
  /**
   * Nodes::Tasks()
   * 
   * @return
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
   * Nodes::tasksUpid()
   * 
   * @return
   */
  public function tasksUpid($node, $upid)
  {
      return self::Request("/nodes/$node/tasks/$upid");
  }
  
  /**
   * Nodes::tasksStop()
   * 
   * @return
   */
  public function tasksStop($node, $upid)
  {
      return self::Request("/nodes/$node/tasks/$upid", null, "DELETE");
  }
  
  /**
   * Nodes::tasksLog()
   * 
   * @return
   */
  public function tasksLog($node, $upid, $limit = null, $start = null)
  {
      $optional['limit']   = !empty($limit) ? $limit : null;
      $optional['start']   = !empty($start) ? $start : null;
      return self::Request("/nodes/$node/tasks/$upid/log", $optional);
  }
  
  /**
   * Nodes::tasksStatus()
   * 
   * @return
   */
  public function tasksStatus($node, $upid)
  {
      return self::Request("/nodes/$node/tasks/$upid/status");
  }
}
