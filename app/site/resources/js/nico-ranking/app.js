import React, { useState } from 'react';
import * as help from './helper';
import FileUpload from './fileUpload';
import SelectRanking from './selectRanking';
import Ranking from './ranking';
import Condition from './condition';
import { NO_SELECT, UPLOAD_DATE_CONDITION, ORDER } from './const';


export default class App extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      rankings: dateList,
      rankingKey: {
        group: NO_SELECT,
        date: NO_SELECT,
      },
      order: Object.keys(ORDER)[0],
      condition: {
        uploadDate: Object.keys(UPLOAD_DATE_CONDITION)[0]
      },
    }
    this.setRankings = this.setRankings.bind(this);
    this.setRankingKey = this.setRankingKey.bind(this);
    this.setOrder = this.setOrder.bind(this);
    this.setCondition = this.setCondition.bind(this);
  }

  setRankings(rankings) {
    this.setState({rankings});
  }
  setRankingKey(group, date) {
    this.setState({rankingKey: {group, date}});

    // const currentRanking = Array.isArray(this.state.rankings[group]) ?
    //       this.state.rankings[group][date] : [];
    const currentRanking = this.state.rankings[group][date];
    if (Array.isArray(currentRanking)
        && currentRanking.length === 0) {

      const params = new URLSearchParams({'kind': group, date});
      fetch(fetchRankingUrl + '?' + params.toString(), {method: 'GET'})
      .then(response => {
        return response.json();
      }).then((response => {
        this.setState({rankings: {
          ...this.state.rankings,
          [group]: {
            ...this.state.rankings[group],
            [date]: response
          }
        }})
      }));
    }
  }
  setOrder(order) {
    this.setState({order});
  }
  setCondition(condition) {
    this.setState({condition: {
      ...this.state.condition,
      ...condition,
    }})
  }

  render() {
    let ranking = help.getRanking(this.state.rankings, this.state.rankingKey);
    ranking = help.narrowDownRanking(ranking, this.state.condition
                                     , new Date(this.state.rankingKey.date));
    ranking = help.sortRanking(ranking, this.state.order);
    // console.log(ranking.map(r => r.rank));
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
          <Condition
            order={this.state.order}
            setOrder={this.setOrder}
            condition={this.state.condition}
            setCondition={this.setCondition}
          />
        </div>
        <Ranking
          ranking={ranking}
        />
      </div>
    )
  }
}





