<?php
namespace Craft;

class Postmaster_ParcelCriteriaModel extends BaseModel
{
    protected $_cachedIds;

    protected $_cachedTotal;

    protected $_matchedParcels;

    protected $_matchedParcelsAtOffsets;

    public function find($attributes = null)
    {   
        $this->setAttributes($attributes);

        if (!isset($this->_matchedParcels))
        {
            $parcels = craft()->postmaster_parcels->find($this);

            $this->setMatchedParcels($parcels);
        }

        return $this->_matchedParcels;
    }

    public function ids($attributes = null)
    {
        $this->setAttributes($attributes);

        if (!isset($this->_cachedIds))
        {
            foreach($this->find() as $row)
            {
                $this->_cachedIds[] = $row->id;
            }
        }

        return $this->_cachedIds;
    }

    public function total($attributes = null)
    {
        $this->setAttributes($attributes);

        if (!isset($this->_cachedTotal))
        {
            $this->_cachedTotal = count($this->find());
        }

        return $this->_cachedTotal;
    }

    public function first($attributes = null)
    {
        $this->setAttributes($attributes);

        return $this->nth(0);
    }

    public function last($attributes = null)
    {
        $this->setAttributes($attributes);

        $total = $this->total();

        if ($total)
        {
            return $this->nth($total-1);
        }
    }

    public function nth($offset)
    {
        if (!isset($this->_matchedParcelsAtOffsets) || !array_key_exists($offset, $this->_matchedParcelsAtOffsets))
        {
            $criteria = new Postmaster_ParcelCriteriaModel($this->getAttributes());
            $criteria->offset = $offset;
            $criteria->limit = 1;
            $elements = $criteria->find();

            if ($elements)
            {
                $this->_matchedParcelsAtOffsets[$offset] = $elements[0];
            }
            else
            {
                $this->_matchedParcelsAtOffsets[$offset] = null;
            }
        }

        return $this->_matchedParcelsAtOffsets[$offset];
    }

    public function copy()
    {
        $class = get_class($this);

        return new $class($this->getAttributes());
    }
    
    public function setAttribute($name, $value)
    {
        if (in_array($name, $this->attributeNames()) && $this->getAttribute($name) === $value)
        {
            return true;
        }

        if (parent::setAttribute($name, $value))
        {
            $this->_matchedParcels = null;
            $this->_matchedParcelsAtOffsets = null;
            $this->_cachedIds = null;
            $this->_cachedTotal = null;

            return true;
        }
        else
        {
            return false;
        }
    }

    public function setMatchedParcels($parcels)
    {
        $this->_matchedParcels = $parcels;

        // Store them by offset, too
        $offset = $this->offset;

        foreach ($this->_matchedParcels as $parcels)
        {
            $this->_matchedParcelsAtOffsets[$offset] = $parcels;
            $offset++;
        }
    }

	protected function defineAttributes()
    {
        return array(
            'title' => AttributeType::String,
            'uid' => AttributeType::String,
            'id' => AttributeType::Number,
            'enabled' => AttributeType::Number,
            'limit' => array('default' => 100, AttributeType::Number),
            'order' => array('default' => 'id', AttributeType::String),
            'offset' => array('default' => 0, AttributeType::String),
            'sort' => array('default' => 'desc', AttributeType::String)
        );
    }
}