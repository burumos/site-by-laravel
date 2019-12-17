import React, { useState } from 'react';
import * as help from './helper';
import FileUpload from './fileUpload';
import SelectRanking from './selectRanking';
import Ranking from './ranking';
import { NO_SELECT } from './const';


export default class App extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      rankings: {},
      rankingKey: {
        group: NO_SELECT,
        date: NO_SELECT,
      },
    }
    this.setRankings = this.setRankings.bind(this);
    this.setRankingKey = this.setRankingKey.bind(this);
  }

  setRankings(rankings) {
    // console.log('set rankings', rankings);
    this.setState({rankings});
  }

  setRankingKey(group, date) {
    // console.log('set key', group, date);
    this.setState({rankingKey: {group, date}});
  }

  render() {
    return (
      <div>
        <div className="rank-select">
          <FileUpload
            rankings={this.state.rankings}
            setRankings={this.setRankings}
          />
          <SelectRanking
            rankings={this.state.rankings}
            setRankingKey={this.setRankingKey}
          />
        </div>
        <Ranking
          ranking={help.getRanking(this.state.rankings, this.state.rankingKey)}
        />
      </div>
    )
  }
}





