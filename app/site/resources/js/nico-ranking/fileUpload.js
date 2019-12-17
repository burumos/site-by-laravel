import React, { useState } from 'react';
import * as help from './helper';

export default function FileUpload({rankings, setRankings}) {
  const [message, setMessage] = useState('');
  const selectFile = function(e) {
    help.getFileContent(e.target.files, rankings)
      .then(newRankings => {
        setRankings(newRankings);
        setMessage('success');
      }, error => {
        setMessage('error: ' + error.message);
      })
  }

  return (
    <div>
      <div>
        nico ranking json file:
        <input type="file"
               onChange={selectFile}/>
      </div>
      <div>{message}</div>
    </div>
  )
}
