public class Driver {
	public static void main(String[] args) {
		String[] auth = {"root", "waterquality"};
		WQIS wqis = new WQIS(auth);
		
		//test API works
		String postData = "{\"test\":[101, 100], \"otherTest\": \"hello world\"}";
		System.out.println(wqis.doPost("api", "apitest", postData));
		
		//get API version
		System.out.println(wqis.doPost("api", "apiversion"));
		
		//get data
		postData = "{\"sites\":[\"101\"],\"startDate\":\"04/06/2004\",\"endDate\":\"10/08/2013\",\"selectedMeasures\":[\"Ecoli\",\"TotalColiform\"],\"category\":\"bacteria\",\"amount\":\"\",\"overUnderSelect\":\">=\",\"measurementSearch\":\"select\",\"aggregate\":false}";
		System.out.println(wqis.doPost("samples", "getRecords", postData));
		
		//get single record with known sample number
		postData = "{\"category\":\"bacteria\", \"sampleNumbers\":[104040302], \"selectedMeasures\":[\"ecoli\"]}";
		System.out.println(wqis.doPost("samples", "getRecords", postData));
		
		//get date range
		postData = "{\"sites\":[\"101\"],\"category\":\"bacteria\"}";
		System.out.println(wqis.doPost("site-locations", "dateRange", postData));
		
		//get latest measures for all sites
		System.out.println(wqis.doPost("site-locations", "latestmeasures"));
		
		postData = "{\"sites\":[101]}";
		System.out.println(wqis.doPost("site-locations", "latestmeasures", postData));
		
		postData = "{\"siteid\":100,\"attributes\":[\"latitude\",\"groups\"]}";
		System.out.println(wqis.doPost("site-locations", "attributes", postData));
	}
}