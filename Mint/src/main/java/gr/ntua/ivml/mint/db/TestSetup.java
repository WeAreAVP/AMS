package gr.ntua.ivml.mint.db;

import java.io.BufferedReader;
import java.io.IOError;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.nio.charset.Charset;


import org.hibernate.Transaction;

public class TestSetup {

	public static String schema = "createSchema.sql";
	public static String testDB = "mop/test/testSetup.sql";
	public static String testClean = "mop/test/testClean.sql";
	
	
	// couldn't find a way to clean the database after the test, so do it before the 
	// test :-)
	public TestSetup() {
		DB.doSQL( schema  );
		DB.doSQL( testClean );
		DB.doSQL( testDB );
	}
}
