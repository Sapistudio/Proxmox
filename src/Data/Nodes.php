<?php

namespace SapiStudio\Proxmox\Data;

class Nodes extends \SapiStudio\Proxmox\Request
{
  public function listNodes()
  {
      return self::Request("/nodes");
  }
  
  public function listNodeQemus($node)
  {
      return self::Request("/nodes/$node/qemu");
  }
  
  public function Tasks($node, $errors = null, $limit = null, $vmid = null, $start = null)
  {
      $optional['errors']  = !empty($errors) ? $errors : false;
      $optional['limit']   = !empty($limit) ? $limit : null;
      $optional['vmid']    = !empty($vmid) ? $vmid : null;
      $optional['start']   = !empty($start) ? $start : null;
      return self::Request("/nodes/$node/tasks", $optional);
  }
  
  public function tasksUpid($node, $upid)
  {
      return self::Request("/nodes/$node/tasks/$upid");
  }
  
  public function tasksStop($node, $upid)
  {
      return self::Request("/nodes/$node/tasks/$upid", null, "DELETE");
  }
  
  public function tasksLog($node, $upid, $limit = null, $start = null)
  {
      $optional['limit']   = !empty($limit) ? $limit : null;
      $optional['start']   = !empty($start) ? $start : null;
      return self::Request("/nodes/$node/tasks/$upid/log", $optional);
  }
  
  public function tasksStatus($node, $upid)
  {
      return self::Request("/nodes/$node/tasks/$upid/status");
  }
}
