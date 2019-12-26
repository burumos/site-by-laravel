import { NO_SELECT } from './const';


export async function getFileContent(files, rankings) {
  return new Promise((resolve, reject) => {
    if (files.length === 0) resolve('');

    const reader = new FileReader();
    reader.onload = (event) => {
      resolve(event.target.result);
    }
    reader.readAsText(files[0]);
  }).then((jsonText => {
    return JSON.parse(jsonText);
  })).then(result => {
    const groupName = /^(\w*)-/.exec(files[0].name)[1];
    let sameGroupRanking = rankings[groupName]
    if (!sameGroupRanking)
      sameGroupRanking = {};

    return {
      ...rankings,
      [groupName]: {
        ...sameGroupRanking,
        ...result,
      }
    }
  })
}

export function getRankingGroups(rankings) {
  if (!isObject(rankings)) return [];

  return Object.keys(rankings).sort();
}

export function getRankingsDate(rankings, group) {
  if (!isObject(rankings)) return [];

  const ranking = rankings[group];
  if (isObject(ranking)) {
    return Object.keys(ranking).sort();
  }
  return [];
}

export function getRanking(rankings, key) {
  if (!isObject(rankings) || !isObject(key)) return [];

  const { group, date } = key;
  if (group == NO_SELECT || date == NO_SELECT) return [];

  return rankings[group][date];
}

export function sortRanking(ranking, order) {
  if (!Array.isArray(ranking)
      || ranking.length === 0)
    return [];

  switch (Number(order)) {
    case 1: // rank asc
      return ranking.sort((a, b) => a.rank > b.rank ? 1 : -1)
    case 2: // rank desc
      return ranking.sort((a, b) => a.rank < b.rank ? 1 : -1)
    case 3: // upload date asc
      return ranking.sort((a, b) => a.uploadDate > b.uploadDate ? 1 : -1)
    case 4: // upload date desc
      return ranking.sort((a, b) => a.uploadDate < b.uploadDate ? 1 : -1)
    default:
      return ranking;
  }
}

export function narrowDownRanking(ranking, condition, baseDate) {
    if (!Array.isArray(ranking)
      || ranking.length === 0)
      return [];

  // baseDateが正しいDateオブジェクトでなければ現在を基準としてDateオブジェクトを作成
  if (!baseDate instanceof Date
      || !baseDate.getTime()) {
    baseDate = new Date();
  }

  switch (Number(condition.uploadDate)) {
    case 1: // 全て
      break;
    case 2: { // 1ヶ月
      baseDate.setMonth(baseDate.getMonth() - 1);
      ranking = ranking.filter(item => compareDate(new Date(item.uploadDate), baseDate));
      break;
    }
    case 3: { // 1週間
      baseDate.setDate(baseDate.getDate() - 7);
      ranking = ranking.filter(item => compareDate(new Date(item.uploadDate), baseDate));
      break;
    }
    case 4: { // 3日
      baseDate.setDate(baseDate.getDate() - 3);
      ranking = ranking.filter(item => compareDate(new Date(item.uploadDate), baseDate));
      break;
    }
    case 5: { // 1日
      baseDate.setDate(baseDate.getDate() - 1);
      ranking = ranking.filter(item => compareDate(new Date(item.uploadDate), baseDate));
      break;
    }
  }

  return ranking;
}

function compareDate(d1, d2) {
  return d1.getTime() > d2.getTime();
}

function isObject(target) {
  return typeof target === 'object' && null !== target;
}

