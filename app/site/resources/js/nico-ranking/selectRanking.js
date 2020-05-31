import React, { useState } from 'react';
import * as help from './helper';
import { NO_SELECT } from './const'

export default function SelectRanking({rankings, setRankingKey}) {
  const [group, setGroup] = useState(NO_SELECT);
  const [date, setDate] = useState(NO_SELECT);
  const handleGroup = group => {
    setGroup(group)
    setDate(NO_SELECT)
    setRankingKey(group, NO_SELECT);
  }
  const handleDate = date => {
    setDate(date)
    setRankingKey(group, date);
  }
  const handleNext = () => {
    const dateAry = help.getRankingsDate(rankings, group);
    for (const index in dateAry) {
      if (dateAry[index] === date) {
        const nextDate = dateAry[Number(index) + 1];
        // 次がない場合は処理なし
        if (!nextDate) return;

        handleDate(nextDate);
        return;
      }
    }
  }

  return (
    <div>
      <div>
        GROUP
        <select
          value={group}
          onChange={e => handleGroup(e.target.value)}
          className="select-group"
        >
          <option value={NO_SELECT}>---</option>
          {help.getRankingGroups(rankings).map(group => (
            <option value={group} key={group} >{group}</option>
          ))}
        </select>
        DATE
        <select
          value={date}
          onChange={e => handleDate(e.target.value)}
          className="select-date"
        >
          <option value={NO_SELECT}>---</option>
          {help.getRankingsDate(rankings, group).map(date => (
            <option value={date} key={date} >{date}</option>
          ))}
        </select>
        <button onClick={handleNext}>next</button>
      </div>
    </div>
  )
}
