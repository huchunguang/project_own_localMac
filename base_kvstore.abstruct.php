<?php
 abstract class base_kvstore_abstract
{
    public function create_key($key)
    {
        return md5(base_kvstore::kvprefix().$this->prefix.$key);
    }
}
