import 'react-native-gesture-handler';
import React, {useState,useEffect} from 'react';
import {SafeAreaView,StyleSheet,ScrollView,View,Text,TextInput,TouchableOpacity,KeyboardAvoidingView,Alert} from 'react-native';
import { KeyboardAwareScrollView } from 'react-native-keyboard-aware-scroll-view';
import {NavigationContainer,useNavigation,useIsFocused} from '@react-navigation/native';
import {createStackNavigator} from '@react-navigation/stack';
import {FontAwesomeIcon} from '@fortawesome/react-native-fontawesome';
import {faEdit,faList,faTimes,faInfo} from '@fortawesome/free-solid-svg-icons';
import { useForm, Controller } from "react-hook-form";
import { Base64 } from 'js-base64';
import NetInfo from "@react-native-community/netinfo";
import SyncStorage from 'sync-storage';
import moment from 'moment';

function numberOfSamples(type) {
  let keys = [];
  keys = SyncStorage.getAllKeys();
  let savedSamples = [], sentSamples = [];
  for (let i = 0; i < keys.length; i++) {
    if (keys[i].charAt(0) == '!') {
      savedSamples.push(<SavedSample key={i} name={keys[i]} />);
    } else {
      sentSamples.push(<SentSample key={i} name={keys[i]} />);
    }
  }
  if (type == "saved") {
    return savedSamples.length;
  } else {
    return sentSamples.length;
  }
}

const NumberOfSamples = (props) => {
  const [number, setNumber] = useState(0);
  const isFocused = useIsFocused();

   useEffect(() => {
    if (props.name == "saved") {
      setNumber(numberOfSamples("saved"));
    } else {
      setNumber(numberOfSamples("sent"));
    }
  }, [isFocused])

  return <Text style={{fontWeight: 'bold'}}>{number}</Text>;
}

function HomeScreen({ navigation }) {
  return (
    <>
    <ScrollView style={{backgroundColor: '#E3F0F6'}}>
      <SafeAreaView style={styles.background}>
        <View>
          <View style={{alignItems: 'center'}}>
            <Text style={{fontSize: 50, color: '#2A5171', marginTop: 20, fontWeight: 'bold'}}>WQIS</Text>
          </View>
          <View style={styles.infoBox}>
            <View style={{flexDirection: 'row'}} >
              <View style={{backgroundColor: '#8F8', padding: 5, alignSelf: "flex-start", borderWidth: 1, borderRadius: 15, opacity: .7, flexDirection: "row"}}>
                <FontAwesomeIcon icon={faInfo} color="black" size={ 16 } style={{alignSelf: 'center'}} />
              </View>
              <Text style={{fontSize: 25, paddingLeft: 6}}>Status</Text>
            </View>
            <Text style={{paddingLeft: 35}}><NumberOfSamples name="saved" /> locally saved sample(s) waiting for your review and resubmission on the 'Submissions' page.</Text>
            <Text style={{paddingLeft: 35}}><NumberOfSamples name="sent" /> sample(s) successfully sent to server this session.</Text>
          </View>
          <View>
            <Text style={{fontSize: 25, color: '#000000', marginTop: 20, marginBottom: 10}}>Select an option:</Text>
          </View>
          <TouchableOpacity
            style={styles.card}
            onPress={() => navigation.navigate('Entry Form', {
              defaultValues: 0, key: ""
            })}>
            <FontAwesomeIcon icon={faEdit} color="white" size={ 40 } />
            <Text style={styles.buttonText}>Enter Data</Text>
          </TouchableOpacity>
          <TouchableOpacity
            style={styles.card}
            onPress={() => navigation.navigate('Submissions')}>
            <FontAwesomeIcon icon={faList} color="white" size={ 40 } />
            <Text style={styles.buttonText}>Submissions</Text>
          </TouchableOpacity>
        </View>
      </SafeAreaView>
    </ScrollView>
    </>
  );
}

function FormScreen({ route, navigation }) {
  const { defaultValues } = route.params;
  const { key } = route.params;
  const { control, handleSubmit, errors } = useForm({ defaultValues });
  const onSubmit = data => submitData(data, { navigation }, key);

  return (
    <>
    <SafeAreaView style={styles.background}>
      <KeyboardAwareScrollView behavior="padding" keyboardShouldPersistTaps={'handled'} style={{backgroundColor: '#E3F0F6'}} showsVerticalScrollIndicator={false}>
        <View>
          <Text style={{fontSize: 25, color: '#000000', marginTop: 20}}>Enter sample data:</Text>
        </View>
        <View style={styles.inputBox}>
          <Text>Site Number</Text>
          <Controller
            as={TextInput}
            control={control}
            name="site_location_id"
            style={styles.textInput}
            onChange={args => args[0].nativeEvent.text}
            rules={{ required: true }}
            placeholder="Site Number..."
            placeholderTextColor="#AAAAAA"
            keyboardType="numeric"
          />
          {errors.site_location_id && <Text style={{color: '#FF0000'}}>This field is required.</Text>}
        </View>
        <View style={styles.inputBox}>
          <Text>Date (mm/dd/yyyy)</Text>
          <Controller
            as={TextInput}
            control={control}
            name="Date"
            style={styles.textInput}
            onChange={args => args[0].nativeEvent.text}
            rules={{ required: true }}
            placeholder="mm/dd/yyyy"
            placeholderTextColor="#AAAAAA"
            keyboardType="numbers-and-punctuation"
          />
          {errors.Date && <Text style={{color: '#FF0000'}}>This field is required.</Text>}
        </View>
        <View style={styles.inputBox}>
          <Text>Sample Number</Text>
          <Controller
            as={TextInput}
            control={control}
            name="Sample_Number"
            style={styles.textInput}
            onChange={args => args[0].nativeEvent.text}
            rules={{ required: true }}
            placeholder="Sample Number..."
            placeholderTextColor="#AAAAAA"
            keyboardType="numeric"
          />
          {errors.Sample_Number && <Text style={{color: '#FF0000'}}>This field is required.</Text>}
        </View>
        <View style={styles.inputBox}>
          <Text>Time (hh:mm)</Text>
          <Controller
            as={TextInput}
            control={control}
            name="Time"
            style={styles.textInput}
            onChange={args => args[0].nativeEvent.text}
            rules={{ required: false }}
            placeholder="hh:mm"
            placeholderTextColor="#AAAAAA"
            keyboardType="numbers-and-punctuation"
          />
          {errors.Time && <Text style={{color: '#FF0000'}}>This field is required.</Text>}
        </View>
        <View style={styles.inputBox}>
          <Text>Bridge to Water Height</Text>
          <Controller
            as={TextInput}
            control={control}
            name="Bridge_to_Water_Height"
            style={styles.textInput}
            onChange={args => args[0].nativeEvent.text}
            rules={{ required: false }}
            placeholder="Bridge to Water Height..."
            placeholderTextColor="#AAAAAA"
            keyboardType="numeric"
          />
          {errors.Bridge_to_Water_Height && <Text style={{color: '#FF0000'}}>This field is required.</Text>}
        </View>
        <View style={styles.inputBox}>
          <Text>Water Temperature</Text>
          <Controller
            as={TextInput}
            control={control}
            name="Water_Temp"
            style={styles.textInput}
            onChange={args => args[0].nativeEvent.text}
            rules={{ required: false }}
            placeholder="Water Temperature..."
            placeholderTextColor="#AAAAAA"
            keyboardType="numeric"
          />
          {errors.Water_Temp && <Text style={{color: '#FF0000'}}>This field is required.</Text>}
        </View>
        <View style={styles.inputBox}>
          <Text>pH</Text>
          <Controller
            as={TextInput}
            control={control}
            name="pH"
            style={styles.textInput}
            onChange={args => args[0].nativeEvent.text}
            rules={{ required: false }}
            placeholder="pH..."
            placeholderTextColor="#AAAAAA"
            keyboardType="numeric"
          />
          {errors.pH && <Text style={{color: '#FF0000'}}>This field is required.</Text>}
        </View>
        <View style={styles.inputBox}>
          <Text>Conductivity</Text>
          <Controller
            as={TextInput}
            control={control}
            name="Conductivity"
            style={styles.textInput}
            onChange={args => args[0].nativeEvent.text}
            rules={{ required: false }}
            placeholder="Conductivity..."
            placeholderTextColor="#AAAAAA"
            keyboardType="numeric"
          />
          {errors.Conductivity && <Text style={{color: '#FF0000'}}>This field is required.</Text>}
        </View>
        <View style={styles.inputBox}>
          <Text>TDS</Text>
          <Controller
            as={TextInput}
            control={control}
            name="TDS"
            style={styles.textInput}
            onChange={args => args[0].nativeEvent.text}
            rules={{ required: false }}
            placeholder="TDS..."
            placeholderTextColor="#AAAAAA"
            keyboardType="numeric"
          />
          {errors.TDS && <Text style={{color: '#FF0000'}}>This field is required.</Text>}
        </View>
        <View style={styles.inputBox}>
          <Text>DO</Text>
          <Controller
            as={TextInput}
            control={control}
            name="DO"
            style={styles.textInput}
            onChange={args => args[0].nativeEvent.text}
            rules={{ required: false }}
            placeholder="DO..."
            placeholderTextColor="#AAAAAA"
            keyboardType="numeric"
          />
          {errors.DO && <Text style={{color: '#FF0000'}}>This field is required.</Text>}
        </View>
        <View style={styles.inputBox}>
          <Text>Turbidity</Text>
          <Controller
            as={TextInput}
            control={control}
            name="Turbidity"
            style={styles.textInput}
            onChange={args => args[0].nativeEvent.text}
            rules={{ required: false }}
            placeholder="Turbidity..."
            placeholderTextColor="#AAAAAA"
            keyboardType="numeric"
          />
          {errors.Turbidity && <Text style={{color: '#FF0000'}}>This field is required.</Text>}
        </View>
        <View style={styles.inputBox}>
          <Text>Comments</Text>
          <Controller
            as={TextInput}
            control={control}
            name="PhysicalComments"
            style={styles.textInput}
            onChange={args => args[0].nativeEvent.text}
            rules={{ 
              required: false
            }}
            placeholder="Comments..."
            placeholderTextColor="#AAAAAA"
            keyboardType="default"
          />
          {errors.PhysicalComments}
        </View>
        <TouchableOpacity
          style={styles.button}
          onPress={handleSubmit(onSubmit)}>
          <Text style={styles.buttonText}>Save / Submit</Text>
        </TouchableOpacity>
      </KeyboardAwareScrollView>
    </SafeAreaView>
    </>
  );
}

function submitData(data, { navigation }, key) {
  NetInfo.fetch().then(state => {
    if (state.isConnected) {
      fetch('http://localhost:8888/wqis/api.json', {
        method: 'POST',
        headers: {
          Accept: 'application/json',
          'Content-Type': 'application/json',
          'Authorization': 'Basic ' + Base64.encode("root:waterquality"),
        },
        body: JSON.stringify(data),
            }).then((response) => { return response.text(); })
          .then((json) => {
            if (json == "Sample data was saved!") {
              Alert.alert(json);
              if (key == "") {
                let date = moment(new Date()).format("MM/DD/YYYY_hh:mm:ss");
                SyncStorage.set(date, data);
                navigation.navigate('Home');
              } else {
                SyncStorage.remove(key);
                let newKey = key.substring(1);
                SyncStorage.set(newKey, data);
                navigation.navigate('Home');
                navigation.navigate('Submissions');
              }
            } else if (json == "Error saving sample data") {
              Alert.alert(json + ". Check your formatting");
            } else {
              Alert.alert("Error in sample data. Check that Site Number exists in server database and that Sample Number is unique");
            }
          })
          .catch((error) => {
            Alert.alert("There was an error connecting to the server. Saving locally...");
            if (key == "") {
              let date = moment(new Date()).format("!MM/DD/YYYY_hh:mm:ss");
              SyncStorage.set(date, data);
              navigation.navigate('Home');
            } else {
              SyncStorage.remove(key);
              SyncStorage.set(key, data);
              navigation.navigate('Home');
              navigation.navigate('Submissions');
            }
          });
    } else {
      Alert.alert("No internet connection detected. Saving locally...");

      if (key == "") {
        let date = moment(new Date()).format("!MM/DD/YYYY_hh:mm:ss");
        SyncStorage.set(date, data);
        navigation.navigate('Home');
      } else {
        SyncStorage.remove(key);
        SyncStorage.set(key, data);
        navigation.navigate('Home');
        navigation.navigate('Submissions');
      }
    }
  });
}

function resendData(key, data, { navigation }) {
  NetInfo.fetch().then(state => {
    if (state.isConnected) {
      fetch('http://localhost:8888/wqis/api.json', {
        method: 'POST',
        headers: {
          Accept: 'application/json',
          'Content-Type': 'application/json',
          'Authorization': 'Basic ' + Base64.encode("root:waterquality"),
        },
        body: JSON.stringify(data),
            }).then((response) => { return response.text(); })
          .then((json) => {
            if (json == "Sample data was saved!") {
              Alert.alert(json);
              let newKey = key.substring(1);
              SyncStorage.set(newKey, data);
              deleteData(key, {navigation});
            } else if (json == "Error saving sample data") {
              Alert.alert(json + ". Some of your sample data may be formatted incorrectly");
            } else {
              Alert.alert("Error in sample data. Check that Site Number exists in server database and that Sample Number is unique");
            }
          })
          .catch((error) => {
            Alert.alert("There was an error connecting to the server. Try again later");
          });
    } else {
      Alert.alert("No internet connection detected. Try again when you have access to WiFi");
    }
  });
}

function getMyValue(key) {
  let ret = JSON.stringify(SyncStorage.get(key), null, 2);
  ret = ret.replace(/[{}"]/g, '');
  ret = ret.replace("site_location_id", 'Site Number');
  ret = ret.replace("Sample_Number", 'Sample Number');
  ret = ret.replace(/[_]/g, ' ');
  Alert.alert(ret);
}

function deletePrompt(key, { navigation }) {
  Alert.alert(
    "",
    "Are you sure you want to delete this data?",
    [
      {
        text: "No",
        style: "cancel"
      },
      { 
        text: "Yes", 
        onPress: () => deleteData(key, { navigation })
      }
    ],
    { cancelable: false }
  );
}

function deleteData(key, { navigation }) {
  SyncStorage.remove(key);
  navigation.navigate("Home");
  navigation.navigate("Submissions"); // reload page
}

const SavedSample = (props) => {
  const navigation = useNavigation();
  return (
    <View style={{flexDirection: "row", alignItems: 'center'}}>
      <Text style={styles.savedSample} onPress={() => getMyValue(props.name)}>{props.name}</Text>
      <TouchableOpacity title="Delete" style={styles.deleteButton} onPress={() => deletePrompt(props.name, {navigation})}>
        <Text>Delete</Text>
      </TouchableOpacity>
      <TouchableOpacity title="Delete" style={styles.editButton} onPress={() => navigation.navigate('Entry Form', { defaultValues: props.data, key: props.name })}>
        <Text>Edit</Text>
      </TouchableOpacity>
      <TouchableOpacity title="Send" style={styles.sendButton} onPress={() => resendData(props.name, SyncStorage.get(props.name), {navigation})}>
        <Text>Send</Text>
      </TouchableOpacity>
    </View>
  );
}

const SentSample = (props) => {
  const navigation = useNavigation();
  return (
    <View style={{flexDirection: "row", alignItems: 'center'}}>
      <Text style={styles.savedSample} onPress={() => getMyValue(props.name)}>{props.name}</Text>
      <TouchableOpacity title="Delete" style={styles.clearButton} onPress={() => deletePrompt(props.name, {navigation})}>
        <FontAwesomeIcon icon={faTimes} color="black" size={ 15 } />
      </TouchableOpacity>
    </View>
  );
}

function SubmissionsScreen({ navigation }) {
  let keys = [];
  keys = SyncStorage.getAllKeys();
  let savedSamples = [], sentSamples = [];
  for (let i = keys.length-1; i >= 0; i--) {
    if (keys[i] != null && keys[i].charAt(0) == '!') {
      savedSamples.push(<SavedSample key={i} name={keys[i]} data={SyncStorage.get(keys[i])} />);
    } else {
      sentSamples.push(<SentSample key={i} name={keys[i]} />);
    }
  }

  return (
    <>
    <ScrollView style={{backgroundColor: '#E3F0F6'}}>
      <SafeAreaView style={styles.background}>
        <View>
          <View style={styles.infoBox}>
            <View style={{backgroundColor: '#8F8', padding: 5, alignSelf: "flex-start", borderWidth: 1, borderRadius: 15, opacity: .7}}>
              <FontAwesomeIcon icon={faInfo} color="black" size={ 16 } style={{alignSelf: 'center'}} />
            </View>
            <Text>This page is used to send locally saved sample data to the server or review recently sent data. Pressing "Delete" or "x" only deletes the data on your device, not the server's database. Press on the blue text to view the sample data.</Text>
          </View>
          <View>
            <Text style={{fontSize: 25, color: '#000000', marginTop: 20, marginBottom: 10}}>Waiting on internet:</Text>
            {savedSamples}
          </View>
          <View>
            <Text style={{fontSize: 25, color: '#000000', marginTop: 20, marginBottom: 10}}>Successfully submitted:</Text>
            {sentSamples}
          </View>
          <TouchableOpacity
            style={styles.button}
            onPress={() => navigation.navigate('Home')}>
            <Text style={styles.buttonText}>Go Back</Text>
          </TouchableOpacity>
        </View>
      </SafeAreaView>
    </ScrollView>
    </>
  );
}

const Stack = createStackNavigator();

const App: () => React$Node = () => {
  return (
    <NavigationContainer>
      <Stack.Navigator
        initialRouteName="Home"
        screenOptions={{
          headerTintColor: 'white',
          headerStyle: {backgroundColor: '#5086A5'},
        }}
      >
        <Stack.Screen 
          name="Home" 
          component={HomeScreen} 
        />
        <Stack.Screen 
          name="Entry Form"
          component={FormScreen} 
        />
        <Stack.Screen 
          name="Submissions"
          component={SubmissionsScreen} 
        />
      </Stack.Navigator>
    </NavigationContainer>
  );
};

const styles = StyleSheet.create({
  card: {
    width: 350,
    marginTop:10,
    paddingTop:30,
    paddingBottom:30,
    backgroundColor:'#2A5171',
    borderRadius:10,
    borderWidth: 1,
    borderColor: '#000',
    alignItems: 'center'
  },
  button: {
    width: 350,
    marginTop: 20,
    paddingTop: 10,
    paddingBottom: 10,
    backgroundColor: '#2A5171',
    borderRadius: 10,
    borderWidth: 1,
    borderColor: '#000'
  },
  sendButton: {
    backgroundColor: 'rgb(150, 255, 150)',
    padding: 5,
    paddingRight: 5,
    paddingLeft: 5,
    marginLeft: 5,
    marginBottom: 5,
    borderRadius: 10,
    borderWidth: 1,
    borderColor: '#000'
  },
  deleteButton: {
    backgroundColor: 'rgb(255, 90, 90)',
    padding: 5,
    paddingLeft: 3,
    paddingRight: 3,
    marginLeft: 'auto',
    marginBottom: 5,
    borderRadius: 10,
    borderWidth: 1,
    borderColor: '#000'
  },
  editButton: {
    backgroundColor: 'rgb(130, 150, 255)',
    padding: 5,
    marginLeft: 5,
    marginBottom: 5,
    borderRadius: 10,
    borderWidth: 1,
    borderColor: '#000'
  },
  clearButton: {
    backgroundColor: 'rgb(255, 90, 90)',
    padding: 5,
    marginLeft: 25,
    marginBottom: 5,
    borderRadius: 20,
    borderWidth: 1,
    borderColor: '#000'
  },
  buttonText: {
    color: '#FFFFFF',
    fontSize: 20,
    textAlign: 'center',
    justifyContent: 'center',
    paddingLeft: 10,
    paddingRight: 10,
  },
  savedSample: {
    fontSize: 18,
    color: '#00F',
    fontWeight: 'bold',
    marginBottom: 10,
    width: 205
  },
  infoBox: {
    backgroundColor: '#F2F0E1', 
    marginTop: 20, 
    width: 350, 
    borderWidth: 1, 
    borderRadius: 10,
    padding: 5, 
    // flexDirection: "row"
  },
  banner: {
    flex: 1, 
    alignItems: 'center', 
    justifyContent: 'center', 
    backgroundColor: '#5086A5',
    //borderBottomWidth: 3
  },
  background: {
    flex: 16, 
    alignItems: 'center', 
    justifyContent: 'center', 
    backgroundColor: '#E3F0F6'
  },
  textInput: {
    height: 40,
    fontSize: 20,
    borderColor: 'gray',
    color: '#000000',
    borderWidth: 1,
    //backgroundColor: 'rgb(240, 250, 255)',
    backgroundColor: '#FFF',
    width: 350,
    paddingLeft: 10
  },
  inputBox: {
    marginTop: 15
  }
});

export default App;