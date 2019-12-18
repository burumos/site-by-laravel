import React, { useState } from 'react';
import * as help from './helper';
import { ORDER, UPLOAD_DATE_CONDITION } from './const'

export default function Condition({order, setOrder, condition, setCondition}) {
  return (
    <div>
      <div>
        ORDER:
        <select
          value={order}
          onChange={e => setOrder(e.target.value)}
        >
          {Object.entries(ORDER).map(([key, label])=> (
            <option key={key} value={key}>{label}</option>
          ))}
        </select>
        published date
        <select
          value={condition['uploadDate']}
          onChange={e =>setCondition({uploadDate: e.target.value})}
        >
          {Object.entries(UPLOAD_DATE_CONDITION).map(([key, label]) => (
            <option key={key} value={key}>{label}</option>
          ))}
        </select>
      </div>
    </div>
  )
}
