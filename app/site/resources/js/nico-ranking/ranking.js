import React, { useState } from 'react';
import * as help from './helper';


export default function Ranking({ ranking }) {
  if (!ranking) return '';

  return (
    <div className="ranking">
      {ranking.map(record => (
        <Item record={record} key={record.videoId}/>
      ))}
    </div>
  )
}

function Item({record}) {
  const [imageShow, setImageShow] = useState(true);

  return (
    <div key={record.videoId} className="rank-item">
      <a href={record.link} target="_blank" className="thumbnail">
        {imageShow &&
         <img
           src={record.thumbnailImage}
           onError={e => setImageShow(false)}/>}
      </a>
      <div className="text">
        <div>
          <span className="rank">{record.rank}</span> :
          <a href={record.link} className="title" target="_blank">
            {record.title}
          </a>
        </div>
        <div className="uploadDate">{record.uploadDate} 投稿</div>
        <div className="description">{record.description}</div>
      </div>
    </div>
  )
}
