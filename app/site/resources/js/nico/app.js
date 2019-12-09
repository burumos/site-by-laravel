import React, { useState, useEffect } from 'react';
import { connect } from 'react-redux';
import { register, changeJsonText, initMylists } from './actions';

class App extends React.Component {
  constructor(props) {
    super(props);
  }

  componentDidMount() {
    const dataElement = document.getElementById('json-data');
    const mylists = JSON.parse(dataElement.dataset['mylists']);
    this.props.initMylists(mylists);
  }

  render() {
    const { items, register } = { ...this.props };
    return (
      <div>
        <RegisterMylist { ...this.props } />
        <Mylist { ...this.props } />
      </div>
    );
  }
}

const mapStateToProps = state => {
  return {
    items: state.items,
    jsonText: state.jsonText,
    jsonMessage: state.jsonMessage,
    mylists: state.mylists,
  };
};

const mapDispachToProps = dispatch => {
  return {
    registerMylist: amount => dispatch(register(amount)),
    changeJsonText: amount => dispatch(changeJsonText(amount)),
    initMylists: amount => dispatch(initMylists(amount)),
  }
}

export default connect(
  mapStateToProps,
  mapDispachToProps
)(App);

function RegisterMylist({ jsonText, jsonMessage, changeJsonText, registerMylist }) {
  return (
    <div>
      <textarea
        style={{width: '80vw'}}
        value={jsonText}
        onChange={e => changeJsonText(e.target.value)}
      />
      <button onClick={() => registerMylist(jsonText)}>register</button>
      <div>{jsonMessage}</div>
    </div>
  );
}

const Mylist = ({ mylists }) => {
  const mylistArray = Object.values(mylists);
  const noSelect = -1;
  const [selectedId, setSelectedId] = useState(noSelect);

  useEffect(() => {
    if (mylistArray.length >= 1
        && selectedId === noSelect) {
      setSelectedId(mylistArray[0].id);
    }
  })

  return (
    <div>
      <div>
        ALL MYLISTS JSON<textarea rows="1" value={ JSON.stringify(mylists) } readOnly ></textarea>
      </div>
      <div>
        mylist:
        <select onChange={ e => setSelectedId(e.target.value) } value={ selectedId }>
          { !Object.keys(mylists) &&
            <option value={noSelect}>----</option>
          }
          { mylistArray.map(mylist => (
            <option value={ mylist.id } key={ mylist.id }>{ mylist.name }</option>
          )) }
        </select>
        <div>
          { mylistArray.filter(mylist => mylist.id == selectedId)
            .map( mylist => mylist.items.map(item => <NicoItem item={item} key={item.id}/> )) }
        </div>
      </div>
    </div>
  )
}

const NicoItem = ({ item }) => {
  return (
    <div className="row mb-3 nico-item">
      <a href={"https://www.nicovideo.jp/watch/" + item.video_id}>
        <img className="mr-3" src={"/nico/image/"+item.video_id} />
      </a>
      <div className="media-body">
        <h5>
          <a href={"https://www.nicovideo.jp/watch/" + item.video_id}
             target="_blank"
          >
            { item.title }
          </a>
        </h5>
        <div>TIME:{item.video_time} / {item.published_at} 投稿 / {item.created_at} 登録</div>
        <div>IMAGE:{item.image_src}</div>
      </div>
    </div>
  )
}
