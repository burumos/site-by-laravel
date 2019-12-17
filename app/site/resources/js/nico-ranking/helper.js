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

function isObject(target) {
  return typeof target === 'object' && null !== target;
}

