import * as actionTypes from './actionTypes';

let initialState = {
  items: [],
  jsonText: '',
  jsonMessage: '',
  mylists: {},
};

export default (state = initialState, action) => {
  switch (action.type) {
    case actionTypes.REGISTER: {
      const { jsonText, jsonMessage, mylists } = action.payload;
      return {
        ...state,
        jsonText,
        jsonMessage,
        mylists: {
          ...state.mylists,
          ...mylists,
        },
      };
    }
    case actionTypes.CHANGE_JSON_TEXT: {
      const { jsonText } = action.payload;
      return {
        ...state,
        jsonText
      }
    }
    case actionTypes.INIT_MYLISTS: {
      const { mylists } = action.payload;
      return {
        ...state,
        mylists,
      }
    }
    default :
      return state;
  }
}
