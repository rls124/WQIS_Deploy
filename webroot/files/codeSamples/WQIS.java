import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.net.CookieHandler;
import java.net.CookieManager;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.Base64;

public class WQIS {
	private String basicAuth;
	private String baseURL;
	
	public WQIS(String[] auth) {
		this(auth, "http://localhost/WQIS/API/");
	}
	
	public WQIS(String[] auth, String baseURL) {
		String userpass = auth[0] + ":" + auth[1];
		basicAuth = "Basic " + new String(Base64.getEncoder().encode(userpass.getBytes()));
		this.baseURL = baseURL;
	}
	
	public String doPost(String controller, String action) {
		return doPost(controller, action, null);
	}
	
	public String doPost(String controller, String action, String postData) {
		String fullPath = baseURL + controller + "/" + action;
		
		//we need to be able to store a cookie to maintain the current session, because of the way data is routed on the server side for API calls
		CookieManager cookieManager = new CookieManager();
		CookieHandler.setDefault(cookieManager);
		
	    PrintWriter out = null;
	    BufferedReader in = null;
	    String result = "";
	    
	    try {
	        //build connection
	    	URL realUrl = new URL(fullPath);
	        HttpURLConnection conn = (HttpURLConnection) realUrl.openConnection();
	        
	        //set request properties
	        conn.setRequestProperty("accept", "*/*");
	        conn.setRequestProperty("connection", "Keep-Alive");
	        conn.setRequestProperty("user-agent", "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)");
	        conn.setRequestProperty ("Authorization", basicAuth);
	        
	        //enable output and input
	        conn.setDoOutput(true);
	        conn.setDoInput(true);
	        out = new PrintWriter(conn.getOutputStream());
	        
	        //send POST DATA
	        out.print(postData);
	        out.flush();
	        in = new BufferedReader(new InputStreamReader(conn.getInputStream()));
	        String line;
	        result = in.readLine();
	        while ((line = in.readLine()) != null) {
	        	result += line;
	        }
	    }
	    catch (Exception e) {
	        e.printStackTrace();
	    }
	    finally {
	        try {
	            if (out != null) {
	                out.close();
	            }
	            if (in != null) {
	                in.close();
	            }
	        }
	        catch (Exception ex) {
	            ex.printStackTrace();
	        }
	    }
	    return result;
	}
}