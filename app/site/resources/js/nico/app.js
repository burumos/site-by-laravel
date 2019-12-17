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

const ITEMS_BY_PAGE = 25;
const Mylist = ({ mylists }) => {
  const mylistArray = Object.values(mylists);
  const noSelect = -1;
  const [selectedId, setSelectedId] = useState(noSelect);
  const [pageNum, setPageNum] = useState(1);

  const items = mylistArray.map(mylist => mylist.id == selectedId
                                ? mylist.items : []).flat();
  let displayItems = items.slice((pageNum - 1) * ITEMS_BY_PAGE
                                 , pageNum * ( ITEMS_BY_PAGE ));

  useEffect(() => {
    if (mylistArray.length >= 1
        && selectedId === noSelect) {
      setSelectedId(mylistArray[0].id);
    }
    setPageNum(1);
  }, [selectedId, mylists])

  return (
    <div>
      <div>
        ALL MYLISTS JSON<textarea rows="1" value={ JSON.stringify(Object.values(mylists)) } readOnly ></textarea>
      </div>
      <div>
        mylist:
        <select onChange={ e => setSelectedId(Number(e.target.value)) } value={ selectedId }>
          { !Object.keys(mylists) &&
            <option value={noSelect}>----</option>
          }
          { mylistArray.map(mylist => (
            <option value={ mylist.id } key={ mylist.id }>{ mylist.name }</option>
          )) }
        </select>
        <MylistPagination page={pageNum} setPageNum={setPageNum} itemCount={items.length}/>
        <div>
          { displayItems.map(item => <NicoItem item={item} key={item.id}/> ) }
        </div>
        <MylistPagination page={pageNum} setPageNum={setPageNum} itemCount={items.length}/>
      </div>
    </div>
  )
}

const MylistPagination = ({page, setPageNum, itemCount}) => {
  const pageCount = Math.round(itemCount / ITEMS_BY_PAGE);

  if (pageCount === 1) return "";

  const handleLinkClick = (e, index) => {
    e.preventDefault();
    setPageNum(index);
    window.scrollTo(0, 260);
  }
  let pagination = [];
  for (let i = 1; i <= pageCount; i++) {
    pagination.push(
      <li className="nav-item" key={i}>
        <a
          className={"nav-link " + (page === i ? "disabled" : "") }
          href="#"
          onClick={e => handleLinkClick(e, i)}
        >
          {i}
        </a>
      </li>
    );
  }

  return (
    <ul className="nav item-nav">
      {pagination}
    </ul>
  )
}

const NicoItem = ({ item }) => {
  return (
    <div className=" nico-item">
      <a href={"https://www.nicovideo.jp/watch/" + item.video_id}
         className="thumbnail">
        <img className="" src={"/nico/image/"+item.video_id} />
      </a>
      <div className="">
        <div>
          <a href={"https://www.nicovideo.jp/watch/" + item.video_id}
             target="_blank"
             className="title"
          >
            { item.title }
          </a>
        </div>
        <div className="date">TIME:{item.video_time} / {item.published_at} 投稿 / {item.created_at} 登録</div>
        <div className="thumbnail-src">THUMBNAIL:{item.image_src}</div>
      </div>
    </div>
  )
}
