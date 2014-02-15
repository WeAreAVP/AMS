package gr.ntua.ivml.mint.concurrent.queue.util;

public interface Generator {
	
	/**
	 * A method which generates a unique key for an arbitrary String value.
	 * @param value The value for which a unique ID will be generated.
	 * @return A fixed size and unique Key generated based on the input value.
	 */
	public String generate(String value);
	
	/**
	 * The normalization should be implemented
	 * taking into consideration both the type of the input and the algorithm used for generating the 
	 * unique IDs.
	 * @param value The value to be normalized.
	 * @return The normalized version of the value parameter.
	 */
	public String normalize(String value);
	
	/**
	 * This method is the same as the generate(String value) but instead of a String representation
	 * of the generated key it returns a byte array with the value.
	 * @param value The value for which the key will be generated.
	 * @return The generated Hex key in bytes.
	 */
	public byte[] generateBytes(String value);
}
