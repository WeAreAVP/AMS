package gr.ntua.ivml.mint.concurrent.queue.util;

import java.io.UnsupportedEncodingException;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

public class SHA1Generator implements Generator {

	public SHA1Generator(){}
	
	@Override
	public String generate(String value) {
		final StringBuilder sbMd5Hash = new StringBuilder();
		MessageDigest m;
		try {
			m = MessageDigest.getInstance("SHA-1");
			m.update(value.getBytes("UTF-8"));

			final byte data[] = m.digest();

			for (byte element : data) {
				sbMd5Hash.append(Character.forDigit((element >> 4) & 0xf, 16));
				sbMd5Hash.append(Character.forDigit(element & 0xf, 16));
			}
		} catch (NoSuchAlgorithmException e) {
			e.printStackTrace();
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		}

		return sbMd5Hash.toString();
	}

	@Override
	public String normalize(String value) {
		return null;
	}

	@Override
	public byte[] generateBytes(String value) {
		return null;
	}

}
